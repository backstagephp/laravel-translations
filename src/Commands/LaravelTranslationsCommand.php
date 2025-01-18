<?php

namespace Vormkracht10\LaravelTranslations\Commands;

use Illuminate\Console\Command;

class LaravelTranslationsCommand extends Command
{
    public $signature = 'laravel-translations';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
