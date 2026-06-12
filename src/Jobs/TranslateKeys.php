<?php

namespace Backstage\Translations\Laravel\Jobs;

use Backstage\Translations\Laravel\Facades\Translator;
use Backstage\Translations\Laravel\Models\Language;
use Backstage\Translations\Laravel\Models\Translation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
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
        return 3;
    }

    /**
     * Calculate the number of seconds to wait before retrying the job.
     */
    public function backoff(): int
    {
        return 30;
    }

    /**
     * Determine the time at which the job should timeout.
     *
     * Must exceed (timeout * tries) plus backoff windows, otherwise long-running
     * attempts fail with MaxAttemptsExceededException before they finish.
     * Also requires the queue connection's retry_after to be greater than
     * timeout (e.g. REDIS_QUEUE_RETRY_AFTER=2400) so that a still-running job
     * is not re-released onto the queue.
     */
    public function retryUntil()
    {
        return now()->addSeconds(($this->timeout + $this->backoff()) * $this->tries());
    }

    public function __construct(public ?Language $lang = null) {}

    public function handle(): void
    {
        ScanTranslationStrings::dispatchSync();

        $locales = $this->lang ? [$this->lang->code] : Language::active()->pluck('code')->toArray();

        $translator = Translator::with(config('translations.translators.default'));

        /**
         * @var Builder $query
         */
        $query = Translation::query();

        $results = $query->whereIn('code', $locales)
            ->whereNull('translated_at')
            ->get();

        $results
            ->each(function (Translation $translation) use ($translator) {
                $newText = $translator->translate($translation->text, $translation->languageCode);

                $translation->update([
                    'text' => $newText,
                    'translated_at' => now(),
                ]);
            });
    }
}
