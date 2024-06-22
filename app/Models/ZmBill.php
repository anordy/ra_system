<?php

namespace App\Models;

use App\Services\Verification\PayloadInterface;
use App\Services\ZanMalipo\ZmResponse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ZmBill extends Model implements Auditable, PayloadInterface
{
    use HasFactory, \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'expire_date' => 'datetime'
    ];

    public static function getPayloadColumns(): array
    {
        return [
            'id',
            'tax_type_id',
            'bill_id',
            'amount',
            'currency',
            'exchange_rate',
            'payer_id',
            'status'
        ];
    }

    public static function getTableName(): string
    {
        return 'zm_bills';
    }

    public function bill_items(){
        return $this->hasMany(ZmBillItem::class, 'zm_bill_id');
    }

    public function bill_payments(){
        return $this->hasMany(ZmPayment::class, 'zm_bill_id');
    }

    public function paidAmount(){
        return $this->bill_payments->sum('paid_amount');
    }

	public function user()
	{
		return $this->morphTo();
	}

    public function is_waiting_callback(){
        return $this->zan_trx_sts_code == ZmResponse::SUCCESS
            && empty($this->control_number)
            && abs(Carbon::parse($this->updated_at)->diffInMinutes(Carbon::now()))<5; //Assumption: request sent less than 5 mins ago
    }
    public function billable(){
        return $this->morphTo();
    }

    public function createdBy(){
        return $this->morphTo('createdby');
    }

    public function bankRecon(){
        return $this->belongsTo(BankRecon::class, 'control_number', 'control_no');
    }

    public function zmRecon(){
        return $this->belongsTo(ZmReconTran::class, 'control_number', 'billctrnum');
    }

    public function payment(){
        return $this->hasOne(ZmPayment::class, 'zm_bill_id');
    }

    public function taxType()
    {
        return $this->belongsTo(TaxType::class);
    }

    public function egaCharges(){
        return $this->hasOne(ZmEgaCharge::class);
    }

    public function pbzTransaction(){
        return $this->belongsTo(PBZTransaction::class, 'control_number', 'control_number');
    }

    public function pbzReversal(){
        return $this->belongsTo(PBZReversal::class, 'control_number', 'control_number');
    }
}
