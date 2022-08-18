<?php

namespace App\Models\Returns;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MmTransferReturnItem extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $table = 'mm_transfer_return_items';

    public function config() {
        return $this->belongsTo(MmTransferConfig::class, 'config_id');
    }

    public function return() {
        return $this->belongsTo(MmTransferReturn::class, 'return_id');
    }
}
