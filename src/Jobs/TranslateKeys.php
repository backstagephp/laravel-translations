<?php

namespace Backstage\Translations\Laravel\Jobs;

use Illuminate\Support\Facades\Lang;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\Translation;

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
        'ai' => \Backstage\Translations\Laravel\Drivers\AITranslator::class,
        'google' => \Backstage\Translations\Laravel\Drivers\GoogleTranslator::class,
    ];

    protected $driver;

    public function __construct(public ?Language $lang = null) {}

    public function handle(): void
    {
        $locales = $this->lang ? [$this->lang->code] : Language::pluck('code')->toArray();

        $configDriver = config('translations.translators.default');

        if (isset($this->defaultDrivers[$configDriver])) {
            $driverClass = $this->defaultDrivers[$configDriver];
            $driver = app($driverClass);

            Translation::whereIn('code', $locales)
                ->whereNull('translated_at')
                ->get()
                ->each(function (Translation $translation) use ($driver) {
                    // $original = Lang::get($translation->key, [], $translation->languageCode);

                    // if($original !== $translation->key) {
                    //     $translation->update([
                    //         'text' => $original,
                    //         'translated_at' => now(),
                    //     ]);

                    //     return;
                    // }

                    $newText = $driver->translate($translation->text, $translation->languageCode);

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
