<?php

namespace App\Http\Livewire\Investigation;

use App\Models\Business;
use App\Models\Investigation\TaxInvestigation;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BusinessInvestigationAddModal extends Component
{

    use LivewireAlert;

    public $name;
    public $description;
    public $business;
    public $business_id;
    public $location_id;
    public $tax_type_id;
    public $to_date;
    public $from_date;
    public $selectedBusiness;
    public $locations = [];
    public $taxTypes = [];


    protected function rules()
    {
        return [
            'name' => 'required',
        ];
    }

    public function mount()
    {
        $this->business = Business::all();
    }

    public function businessChange($id)
    {
        if ($this->business_id) {
            $this->selectedBusiness = Business::with('locations')->find($id);
            $this->taxTypes         = $this->selectedBusiness->taxTypes;
            $this->locations        = $this->selectedBusiness->locations;
        } else {
            $this->reset('taxTypes', 'locations');
        }
    }


    public function submit()
    {
        $this->validate();
        try {
            TaxInvestigation::create([
                'name' => $this->name,
                'created_by' => auth()->user()->id
            ]);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.investigation.business.add-modal');
    }
}
