<?php

namespace Backstage\Translations\Laravel\Models\Concerns;

use Backstage\Translations\Laravel\Models\Language;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasLanguages
{
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'locale', 'code');
    }

    public function setLanguage(Language $language): void
    {
        $this->locale = $language->code;
        $this->save();
    }

    public function setLocale(Language $language): void
    {
        $this->setLanguage($language);
    }
}
