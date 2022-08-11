<?php

namespace App\Models\Relief;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReliefProjectList extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reliefs()
    {
        return $this->hasMany(Relief::class,'project_list_id');
    }

    public function reliefProject()
    {
        return $this->belongsTo(ReliefProject::class,'project_id');
    }

    public function ministry()
    {
        return $this->belongsTo(ReliefMinistry::class);
    }
}
