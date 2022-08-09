<?php

namespace App\Models;

use App\Models\Returns\Vat\VatReturn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialYear extends Model
{
    use HasFactory;

    protected $table = 'financial_years';

    protected $guarded = [];

    public function months()
    {
        return $this->hasMany(FinancialMonth::class);
    }

    public function monthSevenDays()
    {
        return $this->hasMany(SevenDaysFinancialMonth::class);
    }

    public function vat_return()
    {
        return $this->hasMany(VatReturn::class, 'financial_year_id', 'id');
    }
}
