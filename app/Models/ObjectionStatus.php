<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjectionStatus extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const DRAFT = 'draft';
    public const PENDING = 'pending';
    public const APPROVED = 'approved';
    public const CORRECTION = 'correction';
    public const TEMP_CLOSED = 'temp_closed';
    public const DEREGISTERED = 'deregistered';
    public const REJECTED = 'rejected';
    public const CLOSED = 'closed';

    public function objection()
    {
        return $this->belongsTo(Objection::class, 'objection_id');
    }
}
