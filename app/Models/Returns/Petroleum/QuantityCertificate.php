<?php

namespace App\Models\Returns\Petroleum;

use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuantityCertificate extends Model
{
    use HasFactory;

    protected $guarded =  [];

    public function business(){
        return $this->belongsTo(Business::class);
    }

    public function products()
    {
        return $this->hasMany(QuantityCertificateItem::class, 'certificate_id');
    }
}
