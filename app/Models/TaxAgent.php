<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;

class TaxAgent extends Model implements Auditable
{
    use Notifiable, HasFactory, \OwenIt\Auditing\Auditable;

	protected $table = 'tax_agents';

	protected $guarded = [];

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
		return $this->hasOne(RenewTaxAgentRequest::class);
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
