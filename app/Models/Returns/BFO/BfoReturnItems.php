<?php

namespace App\Models\Returns\BFO;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BfoReturnItems extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'bfo_return_items';

    public function config() {
        return $this->belongsTo(BfoConfig::class, 'config_id');
    }

    public function return() {
        return $this->belongsTo(BfoReturn::class, 'return_id');
    }
}
