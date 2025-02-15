<?php

namespace Backstage\Translations\Laravel\Models;

use Illuminate\Database\Eloquent\Model;

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

    public static function booted()
    {
        static::saved(function (Language $language) {
            $defaultExists = static::where('default', true)->exists();

            if ($language->default) {
                static::where('code', '!=', $language->code)->update(['default' => false]);
            } elseif (! $language->default && ! $defaultExists) {
                static::where('code', $language->code)->update(['default' => true]);
            }
        });

        static::deleted(function (Language $language) {
            if ($language->default && static::count() > 0) {
                static::where('code', '!=', $language->code)->first()->update(['default' => true]);
            }
        });

        static::creating(function (Language $language) {
            if (! static::where('default', true)->exists()) {
                $language->default = true;
            }
        });
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
