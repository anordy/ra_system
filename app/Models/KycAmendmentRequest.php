<?php

namespace App\Models;

use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class KycAmendmentRequest extends Model implements Auditable
{
    use HasFactory, WorkflowTrait , \OwenIt\Auditing\Auditable;

    protected $table = 'kyc_amendment_requests';
    protected $guarded  = [];

    public const PENDING = 'pending';
    public const APPROVED = 'approved';
    public const TEMPERED = 'tempered';
    public const REJECTED = 'rejected';

    public function kyc(){
        return $this->belongsTo(KYC::class, 'kyc_id');
    }
    public function approved_by()
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }
    public function rejected_by()
    {
        return $this->belongsTo(User::class, 'rejected_by_id');
    }

    public function created_by_name(){
        return $this->belongsTo(User::class, 'created_by');
    }
}
