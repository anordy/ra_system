<?php

namespace App\Models\Returns;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxReturnCancellationFile extends Model
{
    use HasFactory;

    public function cancellation(){
        return $this->belongsTo(TaxReturnCancellation::class);
    }
}
