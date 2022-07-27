<?php

namespace App\Models\VatReturn;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VatConfigRateHistory extends Model
{
    use HasFactory;
	protected $table = 'vat_config_rate_histories';
	protected $guarded = [];
}
