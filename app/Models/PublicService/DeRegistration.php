<?php

namespace App\Models\PublicService;

use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeRegistration extends Model
{
    use HasFactory, WorkflowTrait;

    protected $table = 'public_service_de_registrations';

    protected $guarded = [];

    public $casts = [
        'de_registration_date' => 'datetime'
    ];

    public function motor(){
        return $this->belongsTo(PublicServiceMotor::class, 'public_service_motor_id');
    }
}
