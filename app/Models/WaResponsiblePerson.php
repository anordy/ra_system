<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaResponsiblePerson extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'wa_responsible_persons';


    public function user() {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function taxpayer() {
        return $this->belongsTo(Taxpayer::class, 'responsible_person_id');
    }

    public function withholdingAgent() {
        return $this->belongsTo(WithholdingAgent::class, 'withholding_agent_id');
    }

}
