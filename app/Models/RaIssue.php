<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaIssue extends Model
{
    const REVENUE_LOSS = 'Revenue Loss';
    const OVERCHARGING = 'Overcharging';

    use HasFactory;

    protected $table = 'ra_issues';


    protected $fillable = [
        'incident_id',
        'type', 
        'detected',
        'prevented',
        'recovered'
    ];

    public function incedent()
    {
        return $this->belongsTo(RaIncedent::class, 'ra_incident_id');
    }
}
