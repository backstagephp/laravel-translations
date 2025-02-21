<?php

namespace Backstage\Translations\Laravel\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Backstage\Translations\Laravel\Observers\LanguageObserver;

#[ObservedBy(LanguageObserver::class)]
class Language extends Model
{
    use HasFactory;

    protected $table = 'languages';

    protected $primaryKey = 'code';

    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;

    protected $casts = [
        'code' => 'string',
        'name' => 'string',
        'native' => 'string',
        'active' => 'boolean',
        'default' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public static function default(): ?Language
    {
        return static::firstWhere('default', true);
    }

    protected static function newFactory()
    {
        $package = Str::before(get_called_class(), 'Models\\');
        $modelName = Str::after(get_called_class(), 'Models\\');
        $path = $package.'Database\\Factories\\'.$modelName.'Factory';

        return $path::new();
    }

    public function translations(): HasMany
    {
        return $this->hasMany(Translation::class);
    }

    public function getLanguageCodeAttribute()
    {
        return explode('-', $this->attributes['code'])[0];
    }

    public function getCountryCodeAttribute()
    {
        return explode('-', $this->attributes['code'])[1];
    }
}
