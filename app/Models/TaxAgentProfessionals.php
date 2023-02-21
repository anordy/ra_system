<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxAgentProfessionals extends Model
{
	use HasFactory, SoftDeletes;
	
	protected $table = 'tax_agent_professionals';
	protected $guarded = [];
}
