<?php

namespace App\Http\Livewire\VatReturn;

use App\Models\VatReturn\VatCategory;
use App\Models\VatReturn\VatConfigRate;
use App\Models\VatReturn\VatConfigRateHistory;
use App\Models\VatReturn\VatService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class RatesModal extends Component
{
	use LivewireAlert;

	public $services, $categories, $service_code, $cat_code, $rate;

	public function mount()
	{
		$this->services = VatService::all();
	}

	public function updated($property){
		if ($property === 'service_code'){
			$this->categories = VatCategory::where('vat_services_code', $this->service_code)->get();
		}
	}

	public function submit()
	{
		$validate = $this->validate([
		  'service_code'=>'required',
			'cat_code'=>'required',
			'rate'=>'required|numeric'
		],
		[
		  'cat_code.required'=>'Category field is required'
		]);

		DB::beginTransaction();

		try {
			$check = VatConfigRate::where('vat_category_code', '=', $this->cat_code)->first();

			if (empty($check))
			{
				$result = new VatConfigRate();
				$result->vat_category_code = $this->cat_code;
				$result->rate = $this->rate;
				$result->created_by = Auth::id();
				$result->save();
			}

			else
			{
				$rate_id = $check->id;
				$exist_rate = $check->rate;
				$exist_vat_category_code = $check->vat_category_code;
				$exist_created_by = $check->created_by;

				$result = VatConfigRate::find($rate_id);
				$result->rate = $this->rate;
				$result->updated_at = now();
				$result->save();

				$hist = new VatConfigRateHistory();
				$hist->vat_config_rates_id = $rate_id;
				$hist->vat_category_code = $exist_vat_category_code;
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
        return view('livewire.vat-return.rates-modal');
    }
}
