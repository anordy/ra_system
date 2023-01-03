<?php

namespace App\Models;

use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TaxpayerAmendmentRequest extends Model implements Auditable
{
    use HasFactory, WorkflowTrait , \OwenIt\Auditing\Auditable;

    protected $table = 'taxpayer_amendment_requests';
	protected $guarded  = [];

    public const PENDING = 'pending';
    public const APPROVED = 'approved';
    public const CORRECTION = 'correction';
    public const REJECTED = 'rejected';

    public function taxpayer(){
        return $this->belongsTo(Taxpayer::class);
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
