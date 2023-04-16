<?php

namespace App\Models\Returns\Port;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortReturnItem extends Model
{
    use HasFactory, SoftDeletes;

       protected $table = 'port_return_items';

    protected $guarded = [];

    public function config()
    {
        return $this->belongsTo(PortConfig::class);
    }

}
