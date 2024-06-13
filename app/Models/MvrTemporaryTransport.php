<?php

namespace App\Models;

use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MvrTemporaryTransport extends Model
{
    use HasFactory, SoftDeletes, WorkflowTrait;

    protected $guarded = [];

    protected $casts = [
        'date_of_travel' => 'date',
        'date_of_return' => 'date',
        'approved_on' => 'date'
    ];

    public function mvr(){
        return $this->belongsTo(MvrRegistration::class, 'mvr_registration_id');
    }

    public function taxpayer(){
        return $this->belongsTo(Taxpayer::class, 'taxpayer_id');
    }

    public function getReferenceNumberAttribute(){
        return "Z-{$this->created_at->timestamp}";
    }

    public function files(){
        return $this->hasMany(MvrTemporaryTransportFile::class, 'mvr_temporary_transport_id');
    }
}
