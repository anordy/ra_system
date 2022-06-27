<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxAgentMount extends Model
{
    use HasFactory;

	public static function getTaxAgentDetails($value,$agent, $academics, $professionals,$trainings ){
		$agent = TaxAgent::find($value);
		$academics = TaxAgent::find($value)->academics;
		$professionals = TaxAgent::find($value)->professionals;
		$trainings = TaxAgent::find($value)->trainings;

	}
}
