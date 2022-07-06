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
		return $this->belongsTo(TaxAgent::class);
	}
}
