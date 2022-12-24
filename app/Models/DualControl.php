<?php

namespace App\Models;

use App\Traits\DualControlActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DualControl extends Model
{
    use HasFactory, DualControlActivityTrait;
    protected $guarded = '';

    //Actions
    public const ADD = 'add';
    public const EDIT = 'edit';
    public const DELETE = 'delete';
    public const DEACTIVATE = 'deactivate';
    public const ACTIVATE = 'activate';

    //Status
    public const NOT_APPROVED = 0;
    public const APPROVE = 1;
    public const REJECT = 2;


    //Models
    public const USER = User::class;
    public const ROLE = Role::class;
    public const CONSULTANT_FEE = TaPaymentConfiguration::class;
    public const SYSTEM_SETTING_CATEGORY = SystemSettingCategory::class;
    public const SYSTEM_SETTING_CONFIG = SystemSetting::class;
    public const INTEREST_RATE = InterestRate::class;
    public const PENALTY_RATE = PenaltyRate::class;

    public function moduleForBlade($model)
    {
        return $this->getModule($model);
    }

}
