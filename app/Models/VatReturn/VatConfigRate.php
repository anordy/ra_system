<?php

namespace App\Models\VatReturn;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VatConfigRate extends Model
{
    use HasFactory;
	protected $table = 'vat_config_rates';
	protected $guarded = [];

	public function vatCategory()
	{
		return $this->belongsTo(VatCategory::class, 'vat_category_code', 'code');
	}
}
