<?php

namespace Backstage\Translations\Laravel\Events;

use Backstage\Translations\Laravel\Models\Language;

class LanguageCodeChanged
{
    public function __construct(public Language $language) {}
}
