<?php

namespace App\Models\Relief;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReliefProject extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reliefs()
    {
        return $this->hasMany(Relief::class,'project_id');
    }
    
}
