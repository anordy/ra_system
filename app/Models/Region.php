<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Region extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    public const UNGUJA = 'unguja';
    public const PEMBA = 'pemba';

    protected $fillable = [
        'code',
        'name',
        'registration_count',
   ];

    public function taxagent()
    {
        return $this->hasOne(TaxAgent::class);
    }

    public function landLeases()
    {
        $this->hasMany(LandLease::class,'region_id');
    }
}
