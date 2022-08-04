<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class District extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'name',
        'region_id'
   ];


   public function region()
   {
        return $this->belongsTo(Region::class);
   }

    public function taxagent()
    {
        return $this->hasOne(TaxAgent::class);
    }

    public function landLeases()
    {
        $this->hasMany(LandLease::class,'district_id');
    }

}
