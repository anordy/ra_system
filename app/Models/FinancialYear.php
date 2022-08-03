<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialYear extends Model
{
    use HasFactory;

    protected $table = 'financial_years';

    protected $guarded = [];

    public function months(){
        return $this->hasMany(FinancialYear::class);
    }
}
