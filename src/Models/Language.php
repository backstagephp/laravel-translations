<?php

namespace Backstage\Translations\Laravel\Models;

use Backstage\Translations\Laravel\Observers\LanguageObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(LanguageObserver::class)]
class Language extends Model
{
    protected $table = 'languages';

    protected $primaryKey = 'code';

    protected $guarded = [];

    public $incrementing = false;

    protected $casts = [
        'code' => 'string',
        'name' => 'string',
        'native' => 'string',
        'active' => 'boolean',
        'default' => 'boolean',
    ];

    public static function default(): ?Language
    {
        return static::firstWhere('default', 1);
    }

    public function getLanguageCodeAttribute()
    {
        return explode('_', $this->attributes['code'])[0];
    }

    public function geCountryCodeAttribute()
    {
        return explode('_', $this->attributes['code'])[1];
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
