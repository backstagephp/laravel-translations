<?php

namespace Vormkracht10\LaravelTranslations\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Vormkracht10\LaravelTranslations\Models\Language;
use Vormkracht10\LaravelTranslations\Models\Translation;

class TranslateKeys implements ShouldQueue
{
    use Queueable;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 2200;

    /**
     * Determine number of times the job may be attempted.
     */
    public function tries(): int
    {
        return 5;
    }

    /**
     * Calculate the number of seconds to wait before retrying the job.
     */
    public function backoff(): int
    {
        return 3;
    }

    /**
     * Determine the time at which the job should timeout.
     */
    public function retryUntil()
    {
        return now()->addMinutes(1);
    }

    protected $defaultDrivers = [
        'openai' => \Vormkracht10\LaravelTranslations\Drivers\OpenAI\Translator::class,
        'google' => \Vormkracht10\LaravelTranslations\Drivers\Google\Translator::class,
    ];

    protected $driver;

    public function __construct(public ?Language $lang = null) {}

    public function handle(): void
    {
        $locales = $this->lang ? [$this->lang->locale] : Language::pluck('locale')->toArray();

        $configDriver = config('translations.translation.driver');

        if (isset($this->defaultDrivers[$configDriver])) {
            $driverClass = $this->defaultDrivers[$configDriver];
            $driver = app($driverClass);

            $translations = Translation::whereIn('locale', $locales)
                ->whereNull('translated_at')
                ->get();

            $translations->each(function (Translation $translation) use ($driver) {
                $newText = $driver->translate($translation->text, $translation->locale);

                $translation->update([
                    'text' => $newText,
                    'translated_at' => now(),
                ]);
            });
        } else {
            logger()->error("Translation driver '{$configDriver}' is not configured.");
        }
    }
}
