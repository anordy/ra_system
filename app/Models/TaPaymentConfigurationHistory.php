<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaPaymentConfigurationHistory extends Model
{
    use HasFactory;
    protected $guarded = [];
	protected $table = 'ta_payment_configuration_history';
}
