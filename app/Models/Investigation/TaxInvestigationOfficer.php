<?php

namespace App\Models\Investigation;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxInvestigationOfficer extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'investigation_id' => 'integer',
    ];

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
