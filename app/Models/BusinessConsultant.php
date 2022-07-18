<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class BusinessConsultant extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function business(){
        return $this->belongsTo(Business::class);
    }

    public function taxpayer(){
        return $this->belongsTo(Taxpayer::class);
    }
}
