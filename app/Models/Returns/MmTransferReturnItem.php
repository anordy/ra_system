<?php

namespace App\Models\Returns;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MmTransferReturnItem extends Model
{
    use HasFactory;

    public function MmTransferConfig() {
        return $this->belongsTo(MmTransferConfig::class, 'config_id');
    }

    public function MmTransferReturn() {
        return $this->belongsTo(MmTransferReturn::class, 'bfo_return_id');
    }
}
