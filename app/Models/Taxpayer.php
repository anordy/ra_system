<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Notifications\Notifiable;

class Taxpayer extends Model implements Auditable
{
    use Notifiable, HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function generateReferenceNo(){
        if ($this->reference_no){
            return true;
        }

        try {
            DB::beginTransaction();
            $s = 'Z';

            switch ($this->region->name){
                case 'Unguja':
                    $s = $s . 'U';
                    break;
                case 'Pemba':
                    $s = $s . 'P';
                    break;
                default:
                    abort(404);
            }

            $s = $s . Carbon::now()->format('y');

            $index = Sequence::where('prefix', 'TRN')->firstOrFail();

            $s = $s . sprintf("%05s", $index->next_id);

            $this->reference_no = $s;
            $this->save();

            // Update index
            $index->next_id = $index->next_id + 1;
            $index->save();

            DB::commit();
        } catch (\Exception $e){
            DB::rollBack();
            Log::error($e->getMessage());
            throw $e;
        }
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function region(){
        return $this->belongsTo(Region::class);
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
}
