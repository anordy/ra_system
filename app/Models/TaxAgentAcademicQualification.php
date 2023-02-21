<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxAgentAcademicQualification extends Model
{
    use HasFactory, SoftDeletes;
    
	protected $table = 'tax_agent_academic_qualifications';

	protected $guarded = [];

	public function taxAgent() {
		return $this->belongsTo('App\Models\TaxAgent');
	}

    public function level()
    {
        return $this->belongsTo(EducationLevel::class, 'id');
    }
}
