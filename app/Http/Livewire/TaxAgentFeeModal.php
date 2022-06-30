<?php

namespace App\Http\Livewire;

use App\Models\TaPaymentConfiguration;
use App\Models\TaPaymentConfigurationHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\TaxAgentFee;

class TaxAgentFeeModal extends Component
{
	use LivewireAlert;

	public $category, $duration, $amount, $no_of_days;

	public function submit()
	{
		$validate = $this->validate([
		  'category'=>'required',
			'amount'=>'required|numeric',
			'duration'=>'required',
			'no_of_days'=>'required'
		],
		[
		  'no_of_days.required'=>'This field is required'
		]
		);

		DB::beginTransaction();
		try {
			$fee = TaPaymentConfiguration::where('category', '=', $this->category)->first();

			if ($fee == null)
			{
				TaxAgentFee::saveFee($this->category, $this->duration, $this->no_of_days, $this->amount, Auth::id());
//				$result = new TaPaymentConfiguration();
//				$result->category = $this->category;
//				$result->duration = $this->duration;
//				$result->no_of_days = $this->no_of_days;
//				$result->amount = $this->amount;
//				$result->created_by = Auth::id();
//				$result->save();
			}

			else
			{
				$cat = $fee->category;
				$id = $fee->id;
				$du = $fee->duration;
				$no = $fee->no_of_days;
				$am = $fee->amount;
				$cr = $fee->created_by;
				TaPaymentConfiguration::where('category', $this->category)->delete();

				TaxAgentFee::saveFee($this->category, $this->duration, $this->no_of_days, $this->amount, Auth::id());

				$hist = new TaPaymentConfigurationHistory();
				$hist->tapc_id = $id;
				$hist->category = $cat;
				$hist->duration = $du;
				$hist->no_of_days = $no;
				$hist->amount = $am;
				$hist->created_by = $cr;
				$hist->save();

			}

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
