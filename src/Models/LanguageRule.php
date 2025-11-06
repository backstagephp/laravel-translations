<?php

namespace Backstage\Translations\Laravel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LanguageRule extends Model
{
    use SoftDeletes;

    protected $table = 'language_rules';

    protected $fillable = [
        'code',
        'name',
    ];

    public function conditions()
    {
        return $this->hasMany(LanguageRuleCondition::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'code', 'code');
    }   
}
