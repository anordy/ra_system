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

    public function bill()
    {
        return $this->hasOne(ZmBillItem::class, 'billable_id')->where('billable_type', get_class($this));
    }

}
