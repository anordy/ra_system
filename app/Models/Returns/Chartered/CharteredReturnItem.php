<?php

namespace App\Models\Returns\Chartered;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharteredReturnItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function config()
    {
        return $this->belongsTo(CharteredReturnConfig::class);
    }
}
