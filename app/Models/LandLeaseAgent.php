<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LandLeaseAgent extends Model
{
    use HasFactory;

    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class,'taxpayer_id');
    }

}
