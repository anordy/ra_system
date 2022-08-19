<?php

namespace App\Models\Returns\ExciseDuty;

use App\Models\Returns\ExciseDuty\MnoConfig;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MnoReturnItem extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'mno_return_items';

    public function config() {
        return $this->belongsTo(MnoConfig::class,'mno_config_id');
    }
}
