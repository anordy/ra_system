<?php

namespace App\Models\Relief;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ReliefProject extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function reliefs()
    {
        return $this->hasMany(Relief::class,'project_id');
    }

    public function reliefProjects()
    {
        return $this->hasMany(ReliefProjectList::class,'project_id');
    }
    
}
