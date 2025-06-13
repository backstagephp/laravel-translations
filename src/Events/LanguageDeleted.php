<?php

namespace Backstage\Translations\Laravel\Events;

use Backstage\Translations\Laravel\Models\Language;

class LanguageDeleted
{
    public function __construct(public Language $language) {}
}
