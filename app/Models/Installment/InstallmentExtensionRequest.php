<?php

namespace App\Models\Installment;

use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentExtensionRequest extends Model
{
    use HasFactory,WorkflowTrait;

    protected $table = 'installment_extensions_requests';

    protected $fillable = [
        'status'
    ];

    public function installable(){
        return $this->morphTo();
    }

    public function installment(){
        return $this->belongsTo(Installment::class,'installable_id');
    }
}
