<?php

namespace App\Models\Relief;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ReliefProjectList extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

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

    public function sponsor(){
        return $this->belongsTo(ReliefSponsor::class, 'relief_sponsor_id');
    }
}
