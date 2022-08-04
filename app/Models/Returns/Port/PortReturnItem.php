<?php

namespace App\Models\Returns\Port;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortReturnItem extends Model
{
    use HasFactory;

       protected $table = 'port_return_items';

    protected $guarded = [];

    public function config()
    {
        return $this->belongsTo(PortConfig::class);
    }

}
