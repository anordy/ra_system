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

	public function requests()
	{
		return $this->hasMany(RenewTaxAgentRequest::class);
	}

	// Scopes
	public function scopeApproved($query){
		return $query->where('status', TaxAgentStatus::APPROVED);
	}

	public function scopePending($query){
		return $query->where('status', TaxAgentStatus::PENDING);
	}

	public function payment()
    {
        return $this->hasOne(ZmBillItem::class, 'billable_id')->where('billable_type', get_class($this));
    }
}
