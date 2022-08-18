<?php

namespace App\Models\Relief;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReliefItems extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function relief()
    {
        return $this->belongsTo(Relief::class,'relief_id');
    }
}
