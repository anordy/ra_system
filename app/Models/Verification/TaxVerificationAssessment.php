<?php

namespace App\Models\Verification;

use App\Models\User;
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

}
