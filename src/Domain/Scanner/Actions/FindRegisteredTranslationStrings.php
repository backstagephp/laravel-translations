<?php

namespace Backstage\Translations\Laravel\Domain\Scanner\Actions;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Lorisleiva\Actions\ActionServiceProvider;

class FindRegisteredTranslationStrings
{
    public static function scan(Collection $locales): Collection
    {
        /** @var \Illuminate\Filesystem\Filesystem $disk */
        $disk = app('files');

        $translationFiles = [];

        $availableLangs = collect($disk->directories(base_path('lang')))
            ->map(fn ($dir) => basename($dir))
            ->all();
        
        foreach ($locales as $locale) {
            $language = in_array($locale, $availableLangs)
                ? $locale
                : (count($availableLangs) ? $availableLangs[0] : null);
        
            if (!$language) {
                continue;
            }
        
            collect($disk->allFiles(base_path("lang/{$language}")))
                ->filter(fn ($file) => $disk->extension($file) === 'php')
                ->each(function ($file) use (&$translationFiles, $locale, $language) {
                    $relativePath = $file->getRelativePathname();
                    $group = str_replace('.php', '', basename($relativePath));
                    $lines = File::getRequire($file->getPathname());
        
                    foreach ($lines as $key => $value) {
                        if ($value !== null) {
                            $translationFiles[$locale][$group . '.' . $key] = $value;
                        }
                    }
                });
        
            if (isset($translationFiles[$locale])) {
                $translationFiles[$locale] = Arr::dot($translationFiles[$locale]);
            }
        }
        
        $translationFiles = collect($translationFiles);

        $loadedProviders = App::getLoadedProviders();
        $instances = [];

        foreach (array_keys($loadedProviders) as $providerClass) {
            if (is_subclass_of($providerClass, ServiceProvider::class)) {
                $instances[] = App::getProvider($providerClass);
            }
        }

        $namespaces = collect($instances)
            ->filter(function (ServiceProvider $provider) {
                return $provider instanceof ActionServiceProvider;
            })
            ->map(function (ServiceProvider $provider) {
                if (method_exists($provider, 'register')) {
                    $provider->register();
                }

                // Booting the provider triggers any callAfterResolving callbacks
                if (method_exists($provider, 'boot')) {
                    $provider->boot();
                }

                $appRef = new \ReflectionProperty($provider, 'app');
                $appRef->setAccessible(true);
                /** @var \Illuminate\Foundation\Application $app */
                $app = $appRef->getValue($provider);

                $app->make('translator');

                $loader = $app->make('translation.loader');

                $namespaces = $loader->namespaces();

                return $namespaces;
            })
            ->first();

        $namespaceBasedTranslations = collect($namespaces)->map(function ($path, $namespace) use ($locales) {
            /** @var \Illuminate\Translation\Translator $translator */
            $translator = clone app('translator');

            $translator->addNamespace($namespace, $path);

            $all = collect($locales)
            ->mapWithKeys(function ($locale) use ($translator) {
                return [$locale => $translator->get(key:'*', locale:$locale)];
            });

            return $all;
        });


        $mergedNamespaceTranslations = collect($locales)->mapWithKeys(function ($locale) use ($namespaceBasedTranslations) {
            return [
                $locale => $namespaceBasedTranslations
                    ->pluck($locale)
                    ->filter()
                    ->reduce(function ($carry, $translations) {
                        if(is_string($translations)) {
                            return [
                                'key' => '*',
                                'namespace' => '*',
                                'group' => '*',
                                'text' => $translations,
                            ];
                        }
                        return array_merge($carry, $translations);
                    }, []),
            ];
        });

        $finalTranslations = collect($locales)->mapWithKeys(function ($locale) use ($mergedNamespaceTranslations, $translationFiles) {
            $namespaceTranslations = $mergedNamespaceTranslations->get($locale, []);
            $fileTranslations = $translationFiles->get($locale, []);

            $merged = collect(array_merge($namespaceTranslations, $fileTranslations))
                ->map(function ($value, $key) {
                   return [
                        'key' => $key,
                        'namespace' => FindTranslatables::extractNamespace($key),
                        'group' => FindTranslatables::extractGroup($key),
                        'text' => $value,
                   ];
                });

            
            return [$locale => $merged];
        });
    
        return $finalTranslations;
    }
}
