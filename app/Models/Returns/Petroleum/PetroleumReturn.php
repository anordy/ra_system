<?php

namespace App\Models\Returns\Petroleum;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\TaxType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetroleumReturn extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function configReturns()
    {
        return $this->hasMany(PetroleumReturnItem::class, 'return_id');
    }

    public function business(){
        return $this->belongsTo(Business::class);
    }

    public function location()
    {
        return $this->belongsTo(BusinessLocation::class);
    }

    public function taxType()
    {
        return $this->belongsTo(TaxType::class);
    }
}
