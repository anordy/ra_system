<?php

namespace App\Models\Returns;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MmTransferPenalty extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'mm_transfer_penalties';

    public function mmTransferPenalties(){
        return $this->belongsTo(MmTransferReturn::class);
    }
}
