<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Notifications\Notifiable;

class Taxpayer extends Model implements Auditable
{
    use Notifiable, HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function identification(){
        return $this->belongsTo(IDType::class, 'id_type');
    }
    
    public function otp()
    {
        return $this->morphOne(UserOtp::class, 'user');
    }

    public function fullname(){
        return $this->first_name. ' '. $this->last_name;
    }

    public function getFullNameAttribute(){
        return "{$this->first_name} {$this->last_name}";
    }


    public function taxAgent(){
        return $this->hasOne(TaxAgent::class);
    }
    

	public function bill(){
		return $this->morphMany(ZmBill::class, 'user');
	}
}
