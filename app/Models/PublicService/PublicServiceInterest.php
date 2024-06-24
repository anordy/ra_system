<?php

namespace App\Models\PublicService;

use App\Models\FinancialMonth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PublicServiceInterest extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function publicServiceReturn(){
        return $this->belongsTo(PublicServiceReturn::class);
    }

    public function financialMonth(){
        return $this->belongsTo(FinancialMonth::class);
    }
}
