<?php

namespace App\Http\Livewire\Returns\StampDuty;

use App\Models\BusinessCategory;
use App\Models\BusinessFileType;
use App\Models\Country;
use App\Models\Returns\FinancialYear;
use App\Models\Returns\StampDutyService;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class AddStampDutyModal extends Component
{

    use LivewireAlert;

    public $financial_year;
    public $name;
    public $code;
    public $rate_type;
    public $rate;
    public $is_active;

    public $financialYears;

    protected $rules = [
        'financial_year' => 'required',
        'name' => 'required',
        'code' => 'nullable',
        'rate_type' => 'required',
        'rate' => 'required',
        'is_active' => 'required',
    ];

    public function mount(){
        $this->financialYears = FinancialYear::all();
    }

    public function submit()
    {
        $this->validate();
        try {
            StampDutyService::create([
                'name' => $this->name,
                'code' => $this->code,
                'rate_type' => $this->rate_type,
                'rate' => $this->rate,
                'business_type' => $this->business_category,
            ]);

            $this->flash('success', 'Business File Type Stored.', [], redirect()->back()->getTargetUrl());
        } catch(Exception $e){
            Log::error($e);

            $this->alert('error', "Couldn't add business file type. Please try again." . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.returns.stamp-duty.add-stamp-duty-modal');
    }
}
