<?php

namespace App\Models\Returns\Vat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VatReturnSupplierDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'vat_return_supplier_details';
    protected $guarded = [];
}
