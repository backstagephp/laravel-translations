<?php

namespace Backstage\Translations\Laravel\Models;

use Backstage\Translations\Laravel\Models\Concerns\HasTranslatableAttributes;
use Backstage\Translations\Laravel\Interfaces\TranslatesAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TranslatableCodeString extends Model implements TranslatesAttributes
{
    use SoftDeletes;
    use HasTranslatableAttributes;

    protected $table = 'translatable_code_strings';

    protected $fillable = [
        'key',
        'text',
        'source_text',
        'namespace',
    ];

    protected $casts = [
        'key' => 'string',
        'text' => 'string',
        'source_text' => 'string',
        'namespace' => 'string',
    ];

    public function getTranslatableAttributes(): array
    {
        return [
            'text',
        ];
    }
}
