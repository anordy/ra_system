<?php

namespace App\Models\Investigation;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxInvestigationAssessment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function taxInvestigation()
    {
        return $this->belongsTo(TaxInvestigation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
