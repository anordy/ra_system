<?php

namespace App\Models\Relief;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReliefMinistry extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function projectList()
    {
        return $this->hasMany(ReliefProjectList::class,'ministry_id');
    }
    
}
