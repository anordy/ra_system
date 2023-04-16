<?php

namespace App\Models\Returns;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MmTransferPenalty extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $table = 'mm_transfer_penalties';

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function return(){
        return $this->belongsTo(MmTransferReturn::class);
    }
}
