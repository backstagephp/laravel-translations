<?php

namespace Backstage\Translations\Laravel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backstage\Translations\Laravel\Contracts\TranslatesAttributes;
use Backstage\Translations\Laravel\Models\Concerns\HasTranslatableAttributes;

class TranslatableCodeString extends Model implements TranslatesAttributes
{
    use HasTranslatableAttributes;
    use SoftDeletes;

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
