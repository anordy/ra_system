<?php

namespace App\Models\PublicService;

use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryClosure extends Model
{
    use HasFactory, WorkflowTrait;

    protected $table = 'public_service_temporary_closures';

    protected $guarded = [];

    public $casts = [
        'closing_date' => 'datetime',
        'opening_date' => 'datetime'
    ];

    public function motor(){
        return $this->belongsTo(PublicServiceMotor::class, 'public_service_motor_id');
    }
}
