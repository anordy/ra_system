<?php

namespace App\Models;

use App\Models\Returns\HotelReturns\HotelReturn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialMonth extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'due_date' => 'datetime'
    ];

    public function year(){
        return $this->belongsTo(FinancialYear::class, 'financial_year_id');
    }

    public function hotelReturns()
    {
        return $this->hasMany(HotelReturn::class);
    }
}
