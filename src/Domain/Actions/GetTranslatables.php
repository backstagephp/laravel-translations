<?php

namespace Vormkracht10\LaravelTranslations\Domain\Actions;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Lorisleiva\Actions\Concerns\AsAction;
use SplFileInfo;

class GetTranslatables
{
    use AsAction;

    public function handle(): Collection
    {
        $functions = collect([
            'trans',
            'trans_choice',
            'Lang::get',
            'Lang::choice',
            'Lang::trans',
            'Lang::transChoice',
            '@lang',
            '@choice',
            '__',
        ]);

        $translations = $this->extractTranslations(collect(config('translations.scan.paths')), $functions);

        $jsontranslations = $this->getTranslationKeysFromPhpFilesInVendor(collect(config('translations.scan.paths')));

        return $translations->merge($jsontranslations)->unique();
    }

    protected function extractTranslations(Collection $paths, Collection $functions): Collection
    {
        return $paths->flatMap(function ($path) use ($functions) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));

            return collect($files)
                ->filter(function (SplFileInfo $file) {
                    return $file->isFile() && in_array($file->getExtension(), config('translations.scan.extensions') ?? ['php', 'blade.php']);
                })
                ->flatMap(function (SplFileInfo $file) use ($functions) {
                    $content = file_get_contents($file->getRealPath());

                    return $functions->flatMap(function ($function) use ($content) {
                        if (preg_match_all("/$function\\(['\"](.+?)['\"]\\)/", $content, $matches)) {
                            return $matches[1];
                        }

                        return [];
                    });
                });
        })
            ->values()
            ->unique();
    }

    protected function getTranslationKeysFromPhpFilesInVendor(Collection $baseDirectories): Collection
    {
        $translations = collect();

        $vendorPath = base_path('vendor');
        $translations = collect();
        
        foreach (File::directories($vendorPath) as $vendorDirectory) {
            foreach (File::directories($vendorDirectory) as $packageDirectory) {
                $packageName = basename($vendorDirectory) . '-' . basename($packageDirectory);
                
                foreach (File::directories($packageDirectory) as $dir) {
                    if (str_contains($dir, 'resources')) {
                        foreach (File::directories($dir) as $resourceDir) {
                            if (str_contains($resourceDir, 'lang')) {
                                foreach (File::directories($resourceDir) as $langDir) {
                                    foreach (File::allFiles($langDir) as $file) {
                                        if ($file->getExtension() === 'php') {
                                            $filename = $file->getBasename('.php');
                                            $content = require $file->getPathname();
                                            if (is_array($content)) {
                                                $namespace = $packageName;
                                                $key = "$namespace::$filename";
                                                $dotted = [];
                                                foreach(Arr::dot($content) as $keyName => $data) {
                                                    $dotted[] = $key.'.'.$keyName;
                                                }

                                                $translations = $translations->merge($dotted);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $translations->unique();
    }
}
