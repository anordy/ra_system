<?php

namespace App\Models;

use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class RenewTaxAgentRequest extends Model implements Auditable
{
    use HasFactory, WorkflowTrait , \OwenIt\Auditing\Auditable;
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
        return $this->morphMany(ZmBill::class, 'billable')->latest();
    }

    public function getBillAttribute(){
        return $this->morphMany(ZmBill::class, 'billable')->latest()->first();
    }
}
