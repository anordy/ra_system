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
    const GENERAL_COMMISSIONER_NAME = 'general-commissioner-name';

    protected $guarded = [];
    
    public function system_setting_category(){
        return $this->belongsTo(SystemSettingCategory::class, 'system_setting_category_id');
    }

    public function scopeCertificatePath($query){
        return $query->where('code', SystemSetting::GENERAL_COMMISSIONER_SIGN)->where('is_approved', 1)->value('value') ?? null;
    }
    public function scopeCommissinerFullName($query){
        return $query->where('code', SystemSetting::GENERAL_COMMISSIONER_NAME)->where('is_approved', 1)->value('value') ?? null;
    }
}
