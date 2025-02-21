<?php

namespace Backstage\Translations\Laravel\Models;

use Backstage\Translations\Laravel\Observers\LanguageObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(LanguageObserver::class)]
class Language extends Model
{
    use HasFactory;

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

    protected static function newFactory()
    {
        $package = Str::before(get_called_class(), 'Models\\');
        $modelName = Str::after(get_called_class(), 'Models\\');
        $path = $package.'Database\\Factories\\'.$modelName.'Factory';

        return $path::new();
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
