<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessDeregistration extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function business() {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function taxpayer() {
        return $this->belongsTo(Taxpayer::class, 'submitted_by');
    }
}
