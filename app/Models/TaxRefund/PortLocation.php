<?php

namespace App\Models\TaxRefund;

use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortLocation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function region() {
        return $this->belongsTo(Region::class, 'region_id');
    }
}
