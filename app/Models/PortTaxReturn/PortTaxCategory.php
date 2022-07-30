<?php

namespace App\Models\PortTaxReturn;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortTaxCategory extends Model
{
    use HasFactory;

	protected $guarded = [];

	public function rates()
	{
		return $this->hasOne(PortTaxConfigRate::class, 'port_tax_category_code', 'code');
	}

	public function portTaxService()
	{
		return $this->belongsTo(PortTaxService::class,'port_tax_service_code', 'code');
	}
}
