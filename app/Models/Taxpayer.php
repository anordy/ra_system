<?php

namespace App\Models;

use App\Services\Verification\PayloadInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Notifications\Notifiable;

class Taxpayer extends Model implements Auditable, PayloadInterface
{
    use Notifiable, HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    protected $auditExclude = [
        'password',
        'remember_token',
        'ci_payload',
        'pass_expired_on'
    ];

    public static function getPayloadColumns(): array
    {
        return [
            'id',
            'email',
            'mobile',
            'status',
            'nida_no',
            'zanid_no',
            'passport_no',
            'permit_number',
            'nida_verified_at',
            'zanid_verified_at',
            'passport_verified_at',
            'biometric_verified_at',
        ];
    }

    public static function getTableName(): string
    {
        return 'users';
    }

    public function generateReferenceNo(){
        if ($this->reference_no){
            return true;
        }

        try {
            DB::beginTransaction();
            $s = 'Z';

            switch ($this->region->location){
                case Region::UNGUJA:
                    $s = $s . 'U';
                    break;
                case Region::PEMBA:
                    $s = $s . 'P';
                    break;
                default:
                    abort(404);
            }

            $s = $s . Carbon::now()->format('y');

            $index = Sequence::where('prefix', 'TRN')->lockForUpdate()->firstOrFail();

            $s = $s . sprintf("%05s", $index->next_id);

            $this->reference_no = $s;
            $this->save();

            // Update index
            $index->next_id = $index->next_id + 1;
            $index->save();

            DB::commit();
        } catch (\Exception $e){
            DB::rollBack();
            Log::error($e);
        }
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function region(){
        return $this->belongsTo(Region::class);
    }
    public function district(){
        return $this->belongsTo(District::class);
    }
    public function ward(){
        return $this->belongsTo(Ward::class);
    }

    public function street(){
        return $this->belongsTo(Street::class);
    }

    public function getLocationAttribute(){
        return $this->region ? $this->region->name : '';
    }

    public function identification(){
        return $this->belongsTo(IDType::class, 'id_type');
    }
    
    public function otp()
    {
        return $this->morphOne(UserOtp::class, 'user');
    }

    public function fullname(){
        return $this->first_name. ' '. $this->last_name;
    }

    public function getFullNameAttribute(){
        return "{$this->first_name} {$this->middle_name} {$this->last_name}";
    }


    public function taxAgent(){
        return $this->hasOne(TaxAgent::class);
    }

	public function bill(){
		return $this->morphMany(ZmBill::class, 'user');
	}

    public function createdLeases()
    {
        return $this->hasMany(LandLease::class, 'created_by');
    }

    public function transport_agent()
    {
        return $this->hasOne(MvrAgent::class, 'taxpayer_id');
    }
    
    public function landLeaseAgent()
    {
        return $this->hasOne(LandLeaseAgent::class, 'taxpayer_id');
    }

    public function landLeases() {
        return $this->hasMany(LandLease::class);
    }

    public function leasePayments() {
        return $this->hasMany(LeasePayment::class);
    }

    public function passwordHistories()
    {
        return $this->morphMany(PasswordHistory::class, 'user');
    }

    public function amendments(){
        return $this->hasMany(TaxpayerAmendmentRequest::class, 'taxpayer_id');
    }

    public function checkPendingAmendment(){
        foreach ($this->amendments()->get() as $amendment){
            if ($amendment['status'] == TaxpayerAmendmentRequest::PENDING){
                return true;
            }
        }
        return false;
    }
}
