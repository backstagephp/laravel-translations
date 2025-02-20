<?php

namespace Backstage\Translations\Laravel\Events;

use Backstage\Translations\Laravel\Models\Language;

class LanguageCreated {
    public function __construct(public Language $language) {}
}