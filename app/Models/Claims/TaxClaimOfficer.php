<?php

namespace App\Models\Claims;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxClaimOfficer extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function taxClaim()
    {
        return $this->belongsTo(TaxClaim::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
