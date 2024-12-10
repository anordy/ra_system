<?php

namespace App\Models\Ntr\Returns;

use App\Models\Taxpayer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NtrVatReturnCancellation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ntr_electronic_vat_return_cancellations';

    protected $guarded = [];

    public function taxpayer() {
        return $this->belongsTo(Taxpayer::class, 'cancelled_by');
    }

}
