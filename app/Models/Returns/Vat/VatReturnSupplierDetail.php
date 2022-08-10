<?php

namespace App\Models\Returns\Vat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VatReturnSupplierDetail extends Model
{
    use HasFactory;
    protected $table = 'vat_return_supplier_details';
    protected $guarded = [];
}
