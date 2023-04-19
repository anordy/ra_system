<?php

namespace App\Models\Returns\Vat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VatCashSales extends Model
{
    use HasFactory;
    protected $table = 'vat_cash_sales';
    protected $guarded = [];
}
