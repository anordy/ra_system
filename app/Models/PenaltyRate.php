<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use ReflectionClass;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenaltyRate extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $guarded = [];

    // Late Filling
    public const LATE_FILLING = 'LF';
    public const LATE_FILLING_NAME = 'Late Filling';

    // Late Payment Before (First period late payment)
    public const LATE_PAYMENT_BEFORE = 'LPB';
    public const LATE_PAYMENT_BEFORE_NAME = 'Late Payment Before';

    // Late Payment After (After first period)
    public const LATE_PAYMENT_AFTER = 'LPA';
    public const LATE_PAYMENT_AFTER_NAME = 'Late Payment After';

    // Whichever Greater Amount (Usually is TZS. 100,000)
    public const WHICH_EVER_GREATER = 'WEG';
    public const WHICH_EVER_GREATER_NAME = 'Which Ever Greater';

    // Penalty for Mobile Month Transfer and Electronic Money Transaction
    public const PENALTY_FOR_MM_TRANSACTION = 'PFMobilesTrans';
    public const PENALTY_FOR_MM_TRANSACTION_NAME = 'Penalty for Mobile Month Transfer and Electronic Money Transaction';

    // 10% of the unpaid balance for each month the rent remains unpaid
    public const LEASE_PENALTY = 'LeasePenaltyRate';
    public const LEASE_PENALTY_NAME = '10% of the unpaid balance for each month the rent remains unpaid';

    // This will be used in creating new penalty rate for a financial year
    public const CONFIGURATIONS = [
        ['code' => self::LATE_FILLING, 'name' => self::LATE_FILLING_NAME, 'rate' => null],
        ['code' => self::LATE_PAYMENT_BEFORE, 'name' => self::LATE_PAYMENT_BEFORE_NAME, 'rate' => null],
        ['code' => self::LATE_PAYMENT_AFTER, 'name' => self::LATE_PAYMENT_AFTER_NAME, 'rate' => null],
        ['code' => self::WHICH_EVER_GREATER, 'name' => self::WHICH_EVER_GREATER_NAME, 'rate' => null],
        ['code' => self::PENALTY_FOR_MM_TRANSACTION, 'name' => self::PENALTY_FOR_MM_TRANSACTION_NAME, 'rate' => null],
        ['code' => self::LEASE_PENALTY, 'name' => self::LEASE_PENALTY_NAME, 'rate' => null],
    ];

    public function year(){
        return $this->belongsTo(FinancialYear::class, 'financial_year_id');
    }
}
