<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class GenericSettingModel extends Model
{
    abstract static function settingTitle();
    abstract static function validationRules();
    abstract static function settingColumn();
}