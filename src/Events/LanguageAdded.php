<?php

namespace Backstage\Translations\Laravel\Events;

use Backstage\Translations\Laravel\Models\Language;

class LanguageAdded
{
    public function __construct(public Language $language) {}
}
