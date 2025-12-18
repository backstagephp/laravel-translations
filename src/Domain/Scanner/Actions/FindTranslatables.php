<?php

namespace Backstage\Translations\Laravel\Domain\Scanner\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

class FindTranslatables
{
    public static string $baseLanguage = 'nl';

    public static string $baseFilename;

    protected static array $allMatches = [];

    public static function scan(bool $mergeKeys = false): Collection
    {
        /**
         * @var Finder $finder
         */
        $finder = new Finder;

        $finder->in(config('translations.scan.paths'))
            ->name(config('translations.scan.extensions'))
            ->files();

        /**
         * @var \Illuminate\Support\Collection $functions
         */
        $functions = collect(config('translations.scan.functions'));

        /**
         * @var string $pattern
         */
        $pattern =
            '[^\w]' .
            '(?<!->)' . // Ignore method chaining
            '(?:' . implode('|', $functions->toArray()) . ')' .
            '\(\s*' .
            '(?:' .
            "'((?:[^'\\\\]|\\\\.)+)'" .  // Match single-quoted keys
            '|' .
            '`((?:[^`\\\\]|\\\\.)+)`' .  // Match backtick-quoted keys
            '|' .
            '"((?:[^"\\\\]|\\\\.)+)"' .  // Match double-quoted keys
            '|' .
            '(\$[a-zA-Z_][a-zA-Z0-9_]*)' . // Match variables
            ')' .
            '\s*' .
            '(?:,([^)]*))?' .  // Capture second argument (parameters)
            '\s*' .
            '[\),]';

        foreach ($finder as $file) {
            if (preg_match_all("/$pattern/siU", $file->getContents(), $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    /**
                     * @var string $key
                     */
                    $key = $match[1] ?: $match[2] ?: $match[3] ?: $match[4];

                    /**
                     * @var string|null $params
                     */
                    $params = $match[5] ?? null;

                    static::addMatch($file, $key, $params);
                }
            }
        }

        return collect(static::$allMatches)
            ->collapse()
            ->map(fn ($match) => [
                'key' => $match['key'],
                'namespace' => $match['namespace'],
                'group' => $match['group'],
                'text' => __($match['key']),
                'params' => $match['params'],
            ])
            ->when($mergeKeys, fn ($collection) => $this->mergeExistingKeys($collection));
    }

    protected static function addMatch($file, $key, $params = null): void
    {
        /**
         * @var string $namespace
         */
        $namespace = static::extractNamespace($key);

        /**
         * @var string $group
         */
        $group = static::extractGroup($key);

        static::$allMatches[$file->getRelativePathname()][] = [
            'key' => $key,
            'namespace' => $namespace,
            'group' => $group,
            'params' => $params,
        ];
    }

    protected static function extractNamespace(string $key): ?string
    {
        /**
         * @var string|null $string
         */
        $string = Str::contains($key, '::') ? explode('::', $key)[0] : null;

        return $string;
    }

    protected static function extractGroup(string $key): ?string
    {
        /**
         * @var string|null $string
         */
        $string = Str::contains($key, '.') ? explode('.', $key)[0] : null;

        return $string;
    }

    protected static function mergeExistingKeys(Collection $newKeys): Collection
    {
        /**
         * @var \Illuminate\Support\Collection $existingKeys
         */
        $existingKeys = collect(json_decode(File::get(static::$baseFilename), true) ?? []);

        /**
         * @var \Illuminate\Support\Collection $unionAble
         */
        $unionAble = $newKeys->filter(fn ($key) => ! $existingKeys->has($key));

        /**
         * @var \Illuminate\Support\Collection $union
         */
        $union = $existingKeys->union($unionAble);

        return $union;
    }
}
