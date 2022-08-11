<?php

namespace App\Models\Verification;

use App\Models\User;
use App\Models\Waiver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxVerificationAssessment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function taxVerification()
    {
        return $this->belongsTo(TaxVerification::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

      public function waivers()
    {
        return $this->hasMany(Waiver::class,'assesment_id');
    }
}
