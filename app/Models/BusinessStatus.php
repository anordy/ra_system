<?php

namespace App\Models;

use App\Models\Business;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinessStatus extends Model
{
    use HasFactory;

    public const DRAFT = 'draft';
    public const PENDING = 'pending';
    public const APPROVED = 'approved';
    public const CORRECTION = 'correction';
    public const TEMP_CLOSED = 'temp_closed';
    public const DEREGISTERED = 'deregistered';
    public const REJECTED = 'rejected';
    public const PBRA_UNVERIFIED = 'unverified';

    protected $guarded = [];

    public function business() {
        return $this->belongsTo(Business::class, 'business_id');
    }

}
