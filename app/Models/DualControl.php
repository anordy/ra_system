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
    public const TRANSFER_FEE = TransactionFee::class;
    public const ZRBBANKACCOUNT = ZrbBankAccount::class;
    public const FINANCIAL_YEAR = FinancialYear::class;
    public const FINANCIAL_MONTH = FinancialMonth::class;
    public const SEVEN_FINANCIAL_MONTH = SevenDaysFinancialMonth::class;
    public const COUNTRY = Country::class;
    public const REGION = Region::class;
    public const DISTRICT = District::class;
    public const WARD = Ward::class;
    public const EXCHANGE_RATE = ExchangeRate::class;
    public const TAX_TYPE = TaxType::class;

    //Messages
    public const SUCCESS_MESSAGE = 'Updated data successfully submitted, Please wait for checker to approve';
    public const ERROR_MESSAGE = 'Something went wrong, Please contact the administrator for help';
    public const RELATION_MESSAGE = 'Operation Failed, This data is already used on another occasion';

    public function moduleForBlade($model)
    {
        return $this->getModule($model);
    }

}
