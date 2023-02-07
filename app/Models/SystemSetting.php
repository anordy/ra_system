<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SystemSetting extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    const PASSWORD_EXPIRATION_DURATION = 'password-expiration-duration';
    const MAXIMUM_NUMBER_OF_ATTEMPTS = 'max-login-attempts';
    const LOGIN_DECAY_MINUTES = 'login-decay-minutes';
    const GENERAL_COMMISSIONER_SIGN = 'general-commissioner-sign';

    protected $guarded = [];
    
    public function system_setting_category(){
        return $this->belongsTo(SystemSettingCategory::class, 'system_setting_category_id');
    }
}
