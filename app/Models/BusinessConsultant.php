<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessConsultant extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function business(){
        return $this->belongsTo(Business::class);
    }

    public function taxpayer(){
        return $this->belongsTo(Taxpayer::class);
    }
}
