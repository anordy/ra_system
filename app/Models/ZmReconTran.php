<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZmReconTran extends Model
{
    use HasFactory;

    public function recon(){
        return $this->belongsTo(ZmRecon::class, 'recon_id');
    }
}
