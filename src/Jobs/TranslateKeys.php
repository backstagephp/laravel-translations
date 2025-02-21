<?php

namespace Backstage\Translations\Laravel\Jobs;

use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\Translation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

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

    public function __construct(public ?Language $lang = null) {}

    public function handle(): void
    {
        ScanTranslationStrings::dispatchSync();

        $locales = $this->lang ? [$this->lang->code] : Language::active()->pluck('code')->toArray();

        $translator = TranslatorManager::with(config('translations.translators.default'));
        $driver = app($driverClass);

        Translation::whereIn('code', $locales)
            ->whereNull('translated_at')
            ->get()
            ->each(function (Translation $translation) use ($driver) {
                $newText = $driver->translate($translation->text, $translation->languageCode);

                $translation->update([
                    'text' => $newText,
                    'translated_at' => now(),
                ]);
            });
}
