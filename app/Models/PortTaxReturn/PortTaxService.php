<?php

namespace App\Models\PortTaxReturn;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortTaxService extends Model
{
    use HasFactory;

	public function portTaxCategory()
	{
		return $this->hasMany(PortTaxCategory::class, 'port_tax_service_code', 'code');
	}
}
