<?php

namespace Backstage\Translations\Laravel\Events;

use Backstage\Translations\Laravel\Models\Language;

class LanguageUpdated
{
    public function __construct(public Language $language) {}
}
