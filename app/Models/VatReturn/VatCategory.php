<?php

namespace App\Models\VatReturn;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VatCategory extends Model
{
    use HasFactory;
	protected $table = 'vat_categories';
	protected $guarded = [];

	public function rates()
	{
		return $this->hasOne(VatConfigRate::class, 'vat_category_code', 'code');
	}
}
