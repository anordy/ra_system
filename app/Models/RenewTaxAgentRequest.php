<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RenewTaxAgentRequest extends Model
{
    use HasFactory;
	protected $table = 'renew_tax_agent_requests';
	protected $guarded  = [];

	public function tax_agent()
	{
		return $this->belongsTo(TaxAgent::class, 'tax_agent_id');
	}

    public function approved_by()
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }
    public function rejected_by()
    {
        return $this->belongsTo(User::class, 'rejected_by_id');
    }

    public function bills(){
        return $this->morphMany(ZmBill::class, 'billable');
    }

    public function getBillAttribute(){
        return $this->morphMany(ZmBill::class, 'billable')->latest()->first();
    }
}
