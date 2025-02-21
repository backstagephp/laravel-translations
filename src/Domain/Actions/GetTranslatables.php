<?php

namespace Backstage\Translations\Laravel\Domain\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\Finder\Finder;

class GetTranslatables
{
    use AsAction;

    public string $baseLanguage = 'nl';

    public string $baseFilename;

    protected array $allMatches = [];

    public function __construct() {}

    public function handle(bool $mergeKeys = false): Collection
    {
        return $this->scan($mergeKeys);
    }

    protected function scan(bool $mergeKeys = false): Collection
    {
        $finder = new Finder;

        $finder->in(config('translations.scan.paths'))
            ->name(config('translations.scan.extensions'))
            ->files()
            ->followLinks();

        $functions = collect(config('translations.scan.functions'));

        $pattern =
            '[^\w]'.
            '(?<!->)'. // Ignore method chaining
            '(?:'.implode('|', $functions->toArray()).')'.
            '\(\s*'.
            '(?:'.
            "'((?:[^'\\\\]|\\\\.)+)'".  // Match single-quoted keys
            '|'.
            '`((?:[^`\\\\]|\\\\.)+)`'.  // Match backtick-quoted keys
            '|'.
            '"((?:[^"\\\\]|\\\\.)+)"'.  // Match double-quoted keys
            '|'.
            '(\$[a-zA-Z_][a-zA-Z0-9_]*)'. // Match variables
            ')'.
            '\s*'.
            '(?:,([^)]*))?'.  // Capture second argument (parameters)
            '\s*'.
            '[\),]';

        foreach ($finder as $file) {
            if (preg_match_all("/$pattern/siU", $file->getContents(), $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $key = $match[1] ?: $match[2] ?: $match[3] ?: $match[4]; // Extract key
                    $params = $match[5] ?? null; // Extract parameters
                    $this->addMatch($file, $key, $params);
                }
            }
        }

        return collect($this->allMatches)
            ->collapse()
            ->map(fn ($match) => [
                'key' => $match['key'],
                'namespace' => $match['namespace'],
                'group' => $match['group'],
                'text' => __($match['key']), // Use Laravel's Lang helper
                'params' => $match['params'],
            ])
            ->when($mergeKeys, fn ($collection) => $this->mergeExistingKeys($collection));
    }

    protected function addMatch($file, $key, $params = null): void
    {
        $namespace = $this->extractNamespace($key);
        $group = $this->extractGroup($key);

        $this->allMatches[$file->getRelativePathname()][] = [
            'key' => $key,
            'namespace' => $namespace,
            'group' => $group,
            'params' => $params,
        ];
    }

    protected function extractNamespace(string $key): ?string
    {
        return Str::contains($key, '::') ? explode('::', $key)[0] : null;
    }

    protected function extractGroup(string $key): ?string
    {
        return Str::contains($key, '.') ? explode('.', $key)[0] : null;
    }

    protected function mergeExistingKeys(Collection $newKeys): Collection
    {
        $existingKeys = collect(json_decode(File::get($this->baseFilename), true) ?? []);

        return $existingKeys->union($newKeys->filter(fn ($key) => ! $existingKeys->has($key)));
    }
}
