<?php

namespace Backstage\Translations\Laravel\Tests\Models;

use Backstage\Translations\Laravel\Contracts\TranslatesAttributes;
use Backstage\Translations\Laravel\Models\Concerns\HasTranslatableAttributes;
use Illuminate\Database\Eloquent\Model;

class TestTranslatableModel extends Model implements TranslatesAttributes
{
    use HasTranslatableAttributes;

    protected $table = 'test_translatable_models';

    protected $fillable = ['title', 'description'];

    public function getTranslatableAttributes(): array
    {
        return ['title', 'description'];
    }
}
