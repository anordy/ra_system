<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;

class TaxAgent extends Model implements Auditable
{
    use Notifiable, HasFactory, \OwenIt\Auditing\Auditable;

	protected $table = 'tax_agents';

	protected $guarded = [];

    public function generateReferenceNo(){
        if ($this->reference_no){
            return true;
        }

        try {
            DB::beginTransaction();
            $s = 'ZC';

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

            $index = Sequence::where('prefix', 'CRN')->firstOrFail();

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

	public function academics() {
		return $this->hasMany('App\Models\TaxAgentAcademicQualification');
	}
	public function professionals()
	{
		return $this->hasMany('App\Models\TaxAgentProfessionals');
	}

	public function trainings()
	{
		return $this->hasMany('App\Models\TaxAgentTrainingExperience');
	}

	public function request()
	{
		return $this->hasMany(RenewTaxAgentRequest::class)->latest();
	}

	// Scopes
	public function scopeApproved($query){
		return $query->where('status', TaxAgentStatus::APPROVED);
	}

	public function scopePending($query){
		return $query->where('status', TaxAgentStatus::PENDING);
	}

    public function bills(){
        return $this->morphMany(ZmBill::class, 'billable');
    }

    public function getBillAttribute(){
        return $this->morphMany(ZmBill::class, 'billable')->latest()->first();
    }


    public function region()
    {
        return $this->belongsTo(Region::class,'region_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class,'district_id');
    }

    public function taxpayer(){
        return $this->belongsTo(Taxpayer::class);
    }
}
