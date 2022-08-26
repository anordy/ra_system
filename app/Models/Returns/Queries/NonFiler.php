<?php

namespace App\Models\Returns\Queries;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\TaxType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonFiler extends Model
{
    use HasFactory;
    protected $table = 'non_filers';
    protected $guarded = [];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }

    public function businessLocation()
    {
        return $this->belongsTo(BusinessLocation::class, 'business_location_id', 'id');
    }

    public function taxType()
    {
        return $this->belongsTo(TaxType::class, 'tax_type_id', 'id');
    }
}
