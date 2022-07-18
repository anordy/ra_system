<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TaPaymentConfiguration extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

	protected $table = 'ta_payment_configurations';

	protected $guarded = [];

}
