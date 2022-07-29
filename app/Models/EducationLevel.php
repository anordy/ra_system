<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationLevel extends Model
{
    use HasFactory;
    protected $table = 'education_levels';
    protected $guarded = [];

    public function academics()
    {
        return $this->hasOne(TaxAgentAcademicQualification::class, 'education_level_id','id');
    }
}
