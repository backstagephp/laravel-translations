<?php

namespace Backstage\Translations\Laravel\Jobs;

use Backstage\Translations\Laravel\Interfaces\TranslatesAttributes;
use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\TranslatableCodeString;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
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

        $locales = $this->lang ? collect([$this->lang->code]) : Language::active()->pluck('code');

        $translatableModels = config('translations.eloquent.translatable-models', [
            TranslatableCodeString::class,
        ]);

        /**
         * @var class-string<Model&TranslatesAttributes>[] $translatableModels
         */
        collect($translatableModels)->each(function ($model) use ($locales) {
            $model::all()
                ->each(function (TranslatesAttributes $record) use ($locales) {
                    $locales->each(function ($locale) use ($record) {
                        info('Translating attributes:'.json_encode($record->getTranslatableAttributes())." for locale: $locale in model: ".$record->id);
                        $record->translateAttributes($locale);
                    });
                });
        });
    }
}
