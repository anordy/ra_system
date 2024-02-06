<?php

namespace App\Models\PropertyTax;

use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentExtension extends Model
{
    use HasFactory, SoftDeletes, WorkflowTrait;

    protected $table = 'property_payment_extensions';

    protected $guarded = [];

    public function propertyPayment(){
        return $this->belongsTo(PropertyPayment::class, 'property_payment_id');
    }
}
