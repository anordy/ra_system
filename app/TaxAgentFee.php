<?php

namespace App;

use App\Models\TaPaymentConfiguration;
use Illuminate\Support\Facades\Auth;

class TaxAgentFee
{
	public static function saveFee($category, $duration, $no, $amount, $id)
	{
		$result = new TaPaymentConfiguration();
		$result->category = $category;
		$result->duration = $duration;
		$result->no_of_days = $no;
		$result->amount = $amount;
		$result->created_by = $id;
		$result->save();
	}

}