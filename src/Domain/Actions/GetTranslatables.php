<?php

namespace Vormkracht10\LaravelTranslations\Domain\Actions;

use SplFileInfo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Lorisleiva\Actions\Concerns\AsAction;

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

        $jsontranslations = $this->getTranslationKeys(collect(config('translations.scan.paths')));

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
    protected function getTranslationKeys(Collection $baseDirectories): Collection
    {
        $translations = collect();

        foreach ($baseDirectories as $baseDirectory) {
            $vendorLangPath = $baseDirectory . '/vendor';
            if (File::exists($vendorLangPath)) {
                $vendorDirectories = File::directories($vendorLangPath);

                foreach ($vendorDirectories as $vendorDirectory) {
                    if (File::exists($vendorDirectory . '/lang')) {
                        $vendorLangFiles = File::allFiles($vendorDirectory . '/lang');
                        foreach ($vendorLangFiles as $file) {
                            $fileName = pathinfo($file)['filename'];
                            $vendor = basename($vendorDirectory);  // Package/vendor name
                            $translations[$vendor][$fileName] = require $file->getPathname();
                        }
                    }
                }
            }
        }

        return $translations;
    }
    
    
}
