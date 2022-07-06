<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Events\SendMail;
use App\Events\SendSms;
use OwenIt\Auditing\Contracts\Auditable;

class WithholdingAgent extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function taxpayer() {
        return $this->belongsTo(Taxpayer::class, 'responsible_person_id');
    }

    public function ward() {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    public function district() {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function region() {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function sendSuccessfulRegistrationNotification()
    {
        if (!$this->taxpayer) {
            throw new \Exception("No Taxpayer found.");
        }

        try {
            event(new SendMail('withholding_agent_registration', $this->id));
            event(new SendSms('withholding_agent_registration', $this->id));
            return true;
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }
    }

}
