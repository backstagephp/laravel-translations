<?php

namespace Vormkracht10\LaravelTranslations\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Vormkracht10\LaravelTranslations\Models\Language;
use Vormkracht10\LaravelTranslations\Models\Translation;

class TranslateKeys implements ShouldQueue
{
    use Queueable;

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

            $translations = Translation::whereIn('locale', $locales)->get();

            $translations->each(function (Translation $translation) use ($driver) {
                $newText = $driver->translate($translation->text, $translation->locale);

                $translation->update(['text' => $newText]);

            });
        } else {
            logger()->error("Translation driver '{$configDriver}' is not configured.");
        }
    }
}
