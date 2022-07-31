<?php

namespace App\Models\PortTaxReturn;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortTaxConfigRate extends Model
{
    use HasFactory;
	protected $guarded = [];

	public function portTaxCategory()
	{
		return $this->belongsTo(PortTaxCategory::class, 'port_tax_category_code', 'code');
	}
}
