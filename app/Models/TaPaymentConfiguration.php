<?php

namespace App\Models;

use App\Services\Verification\PayloadInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TaPaymentConfiguration extends Model implements Auditable, PayloadInterface
{
    use HasFactory, \OwenIt\Auditing\Auditable;

	protected $table = 'ta_payment_configurations';

	protected $guarded = [];


    public static function getPayloadColumns(): array
    {
        return [
            'id',
            'category',
            'duration',
            'amount'
        ];
    }

    public static function getTableName(): string
    {
        return 'ta_payment_configurations';
    }
}
