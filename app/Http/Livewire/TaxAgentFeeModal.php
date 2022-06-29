<?php

namespace App\Http\Livewire;

use App\Models\TaPaymentConfiguration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class TaxAgentFeeModal extends Component
{
	use LivewireAlert;

	public $category, $duration, $amount, $no_of_days, $no_of_months, $no_of_years;

	public function submit()
	{
		$validate = $this->validate([
		  'category'=>'required',
			'amount'=>'required|numeric',
//			'duration'=>'required'
		]);

		DB::beginTransaction();


//		dd($this->no_of_years);
		try {
			$result = new TaPaymentConfiguration();
			$result->category = $this->category;
			$result->duration = $this->duration;
			if (!empty($this->no_of_days))
			{
				$result->no_of_days = $this->no_of_days;
			}
			if (!empty($this->no_of_months))
			{
				$result->no_of_days = $this->no_of_months;
			}
			if (!empty($this->no_of_years))
			{
				$result->no_of_days = $this->no_of_years;
			}
			$result->amount = $this->amount;
			$result->created_by = Auth::id();
			$result->save();
			DB::commit();
			$this->flash('success', 'Saved successfully', [], redirect()->back()->getTargetUrl());

		}

		catch (\Throwable $exception)
		{
			Log::error($exception);
			$this->flash('warning', 'Internal server error', [], redirect()->back()->getTargetUrl());

		}
	}
	public function render()
    {
        return view('livewire.tax-agent-fee-modal');
    }
}
