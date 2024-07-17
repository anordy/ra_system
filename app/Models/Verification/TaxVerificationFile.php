<?php

namespace App\Models\Verification;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxVerificationFile extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function verification()
    {
        return $this->belongsTo(TaxVerification::class, 'tax_verification_id');
    }

}
