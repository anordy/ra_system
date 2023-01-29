<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxAgentMount extends Model
{
    use HasFactory;

	public static function getTaxAgentDetails($value,$agent, $academics, $professionals,$trainings ){
		$agent = TaxAgent::findOrFail($value);
		$academics = TaxAgent::findOrFail($value)->academics;
		$professionals = TaxAgent::findOrFail($value)->professionals;
		$trainings = TaxAgent::findOrFail($value)->trainings;

	}
}
