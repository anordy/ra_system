<?php

namespace App\Models\Ntr;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NtrBusinessOwner extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function country() {
        return $this->belongsTo(Country::class, 'nationality_id');
    }
}
