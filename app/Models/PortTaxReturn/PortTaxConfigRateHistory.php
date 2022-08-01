<?php

namespace App\Models\PortTaxReturn;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortTaxConfigRateHistory extends Model
{
    use HasFactory;
	protected $table = 'port_tax_config_rate_history';
	protected $guarded = [];
}
