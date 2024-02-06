<?php

namespace App\Models\PropertyTax;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentInterest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'property_payment_interests';

    protected $guarded = [];
}
