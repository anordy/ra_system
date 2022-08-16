<?php

namespace App\Models\Returns\BFO;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BfoConfig extends Model
{
    use HasFactory;

    protected $table = 'bfo_configs';

    public function items() {
        return $this->hasMany(BfoReturnItems::class);
    }
}
