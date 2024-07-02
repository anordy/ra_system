<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SystemSettingCategory extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public const CERTIFICATESETTINGS_ID = 3;

    public const PASSWORD_POLICY = 'password-policy';
    public const LOGIN_SETTINGS = 'login-settings';
    public const CERTIFICATE_SETTINGS = 'certificate-settings';
    public const FILING_DEADLINE = 'filing-deadline';
    public const OTHER = 'other';

    public function system_settings(){
        return $this->hasMany(SystemSetting::class);
    }
}
