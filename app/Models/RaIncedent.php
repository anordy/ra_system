<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaIncedent extends Model
{
    CONST PENDING = 'PENDING';
    CONST APPROVED = 'APPROVED';
    CONST REJECTED = 'REJECTED';
    CONST CORRECTION = 'CORRECTION';
    
    use HasFactory;

    protected $table = 'ra_incedents';

        protected $fillable = [
            'reference',
            'bank_channel_id',
            'name',
            'real_issue',
            'symptom_of_incident',
            'impact_revenue',
            'impact_customers',
            'impact_system',
            'incident_reported_date',
            'status',
            'reported_by',
            'owner_by',
            'affected_rev_stream',
            'bank_system_id',
            'action_taken',
            'additional_ra',
        ];


        public function channel(){
            return $this->belongsTo(BankChannel::class, 'bank_channel_id');
        }
        public function revenue(){
            return $this->hasMany(RaIssue::class,'ra_incident_id');
        }
        public function system(){
            return $this->belongsTo(BankSystem::class, 'bank_system_id');
        }
        public function reportedBy(){
            return $this->belongsTo(User::class, 'reported_by');
        }
        public function owner(){
            return $this->belongsTo(User::class, 'owner_by');
        }
}
