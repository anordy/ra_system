<?php

namespace App\Models\Returns\Vat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VatCashSales extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'vat_cash_sales';
    protected $guarded = [];
}
