<?php

namespace App;

use App\Models\TaPaymentConfiguration;
use Illuminate\Support\Facades\Auth;

class TaxAgentFee
{
	public static function saveFee($category, $duration, $nationality, $amount, $currency, $id)
	{
		$result = new TaPaymentConfiguration();
		$result->category = $category;
		$result->duration = $duration;
		$result->is_citizen = $nationality;
		$result->amount = $amount;
		$result->currency = $currency;
		$result->created_by = $id;
		$result->save();
	}

}