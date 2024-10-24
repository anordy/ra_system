<?php

namespace App\Models\Ntr;

use App\Models\Taxpayer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NtrBusinessDeregistration extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function taxpayer() {
        return $this->belongsTo(Taxpayer::class, 'ntr_taxpayer_id');
    }

    public function business() {
        return $this->belongsTo(NtrBusiness::class, 'ntr_business_id');
    }
}
