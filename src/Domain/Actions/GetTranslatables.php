<?php

namespace Vormkracht10\LaravelTranslations\Domain\Actions;

use Illuminate\Support\Collection;
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

        return $translations;
    }

    protected function extractTranslations(Collection $paths, Collection $functions): Collection
    {
        return $paths->flatMap(function ($path) use ($functions) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));

            return collect($files)
                ->filter(function ($file) {
                    return $file->isFile() && in_array($file->getExtension(), config('translations.scan.extensions') ?? ['php', 'blade.php']);
                })
                ->flatMap(function ($file) use ($functions) {
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
}
