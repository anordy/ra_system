<?php

namespace App\Http\Livewire\Settings\HotelLevyReturns;

use App\Models\HotelLevyConfig;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class HotelLevyConfigAddModal extends Component
{

    use LivewireAlert;

    public $name;
    public $code;
    public $is_rate_charged = false;
    public $is_rate_in_percentage = true;
    public $rate_in_percentage;
    public $rate_in_amount;
    public $status = 'active';
    public $financial_year = '2022';


    protected function rules()
    {
        return [
            'name' => 'required',
            'code' => 'required',
            'is_rate_in_percentage' => 'required',
            'is_rate_charged' => 'required',
            'rate_in_percentage' => 'exclude_if:is_rate_in_percentage,false|exclude_if:is_rate_charged,false|required_if:is_rate_in_percentage,true',
            'rate_in_amount' => 'exclude_if:is_rate_in_percentage,true|exclude_if:is_rate_in_charged,false|required_if:is_rate_in_percentage,false',
            'status' => 'required',
            'financial_year' => 'required',
        ];
    }

    public function submit()
    {
        $this->validate();

        try {

            $hotel_levy_config = HotelLevyConfig::create([
                'name' => $this->name,
                'code' => $this->code,
                'is_rate_in_percentage' => $this->is_rate_in_percentage,
                'rate_in_amount' => $this->rate_in_amount,
                'rate_in_percentage' => $this->rate_in_percentage,
                'status' => $this->status,
                'financial_year' => $this->financial_year
            ]);

            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());

        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.settings.hotel.hotel-levy-config-add-modal');
    }
}
