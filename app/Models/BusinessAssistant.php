<?php

namespace App\Models;

use App\Enum\AssistantStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessAssistant extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function taxpayer(){
        return $this->belongsTo(Taxpayer::class);
    }

    public function business(){
        return $this->belongsTo(Business::class);
    }

    public function scopeActive($query){
        return $query->where('status', AssistantStatus::ACTIVE);
    }
}
