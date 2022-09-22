<?php

namespace App\Models\Installment;

use App\Enum\BillStatus;
use App\Enum\InstallmentStatus;
use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\Returns\TaxReturn;
use App\Models\TaxType;
use App\Models\ZmBill;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Installment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'installment_from' => 'datetime',
        'installment_to' => 'datetime',
    ];

    public function items(){
        return $this->hasMany(InstallmentItem::class);
    }

    public function request(){
        return $this->belongsTo(InstallmentRequest::class, 'installment_request_id');
    }

    public function taxReturn(){
        return $this->belongsTo(TaxReturn::class);
    }

    public function business(){
        return $this->belongsTo(Business::class);
    }

    public function location(){
        return $this->belongsTo(BusinessLocation::class, 'location_id');
    }

    public function taxtype(){
        return $this->belongsTo(TaxType::class,'tax_type_id');
    }

    public function bill(){
        return $this->morphOne(ZmBill::class, 'billable');
    }

    public function paidAmount(){
        return $this->items()->where('status', BillStatus::COMPLETE)->sum('amount');
    }

    /**
     * @return bool|Carbon
     */
    public function getNextPaymentDate(){
        if (!$this->status == InstallmentStatus::ACTIVE){
            return false;
        }

        if ($this->items()->where('status', BillStatus::COMPLETE)->count() >= $this->installment_count){
            return false;
        }

        return $this->installment_from->addMonths($this->items()->where('status', BillStatus::COMPLETE)->count() + 1);
    }

    public function files(){
        return $this->hasMany(InstallmentRequestFile::class);
    }
}
