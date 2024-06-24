<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class DlRestriction extends GenericSettingModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'dl_restrictions';

    protected $fillable = [
        'code',
        'description',
        'symbol',
    ];

    static function settingTitle()
    {
        return "Driving License Restrictions";
    }

    static function validationRules()
    {
        return [
            'data.code' => ['required', 'gs_unique'],
            'data.symbol' => ['required', 'gs_unique'],
            'data.description' => ['required']
        ];
    }

    static function settingColumn()
    {
        // TODO: Implement settingColumn() method.
    }
}
