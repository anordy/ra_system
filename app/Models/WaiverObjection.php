<?php

namespace App\Models;

use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaiverObjection extends Model
{
    use HasFactory;

        use HasFactory, WorkflowTrait;

    protected $guarded = [];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function waiverStatus()
    {
        return $this->hasOne(WaiverStatus::class);
    }

   
}
