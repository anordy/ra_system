<?php

namespace App\Http\Livewire\PortTaxReturn;

use App\Models\PortTaxReturn\PortTaxCategory;
use App\Models\PortTaxReturn\PortTaxConfigRate;
use App\Models\PortTaxReturn\PortTaxConfigRateHistory;
use App\Models\PortTaxReturn\PortTaxService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RatesModal extends Component
{
	use LivewireAlert;

	public $services, $categories, $service_code, $cat_code, $rate;

	public function mount()
	{
		$this->services = PortTaxService::all();
	}

	public function updated($property){
		if ($property === 'service_code'){
			$this->categories = PortTaxCategory::where('port_tax_service_code', $this->service_code)->get();
		}
	}

	public function submit()
	{
		$validate = $this->validate([
		  'service_code'=>'required|strip_tag',
		  'cat_code'=>'required|strip_tag',
		  'rate'=>'required|numeric'
		],
		  [
			'cat_code.required'=>'Category field is required'
		  ]);

		DB::beginTransaction();

		try {
			$check = PortTaxConfigRate::where('port_tax_category_code', '=', $this->cat_code)->first();

			if (empty($check))
			{
				$result = new PortTaxConfigRate();
				$result->port_tax_category_code = $this->cat_code;
				$result->rate = $this->rate;
				$result->created_by = Auth::id();
				$result->save();
			}

			else
			{
				$rate_id = $check->id;
				$exist_rate = $check->rate;
				$exist_vat_category_code = $check->port_tax_category_code;
				$exist_created_by = $check->created_by;

				$result = PortTaxConfigRate::find($rate_id);
				$result->rate = $this->rate;
				$result->updated_at = now();
				$result->save();

				$hist = new PortTaxConfigRateHistory();
				$hist->port_tax_config_rates_id = $rate_id;
				$hist->port_tax_category_code = $exist_vat_category_code;
				$hist->rate = $exist_rate;
				$hist->created_by = $exist_created_by;
				$hist->save();

			}

			DB::commit();
			$this->flash('success', 'Saved successfully', [], redirect()->back()->getTargetUrl());
		}

		catch (\Throwable $exception)
		{
			DB::rollBack();
			Log::error($exception);
			$this->flash('warning', 'Internal server error', [], redirect()->back()->getTargetUrl());
		}

	}
    public function render()
    {
        return view('livewire.port-tax-return.rates-modal');
    }
}
