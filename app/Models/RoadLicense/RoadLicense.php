<?php

namespace App\Models\RoadLicense;

use App\Models\MvrRegistration;
use App\Models\Taxpayer;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoadLicense extends Model
{
    use HasFactory, SoftDeletes, WorkflowTrait;

    protected $guarded = [];

    public function registration() {
        return $this->belongsTo(MvrRegistration::class, 'mvr_registration_id');
    }

    public function taxpayer() {
        return $this->belongsTo(Taxpayer::class, 'taxpayer_id');
    }
}
