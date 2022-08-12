<?php

namespace App\Http\Livewire\Audit;

use App\Models\Business;
use App\Models\TaxAudit\TaxAudit;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BusinessAuditAddModal extends Component
{

    use LivewireAlert;

    public $name;
    public $description;
    public $business;
    public $business_id;
    public $location_id;
    public $tax_type_id;
    public $intension;
    public $scope;
    public $period_from;
    public $period_to;

    public $selectedBusiness;
    public $locations = [];
    public $taxTypes = [];


    protected function rules()
    {
        return [
            'business_id' => 'required',
            'location_id' => 'required',
            'tax_type_id' => 'required',
            'intension' => 'required',
            'scope' => 'required',
            'period_from' => 'required',
            'period_to' => 'required',
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
            TaxAudit::create([
                'business_id' => $this->business_id,
                'location_id' => $this->location_id,
                'tax_type_id' => $this->tax_type_id,
                'intension' => $this->intension,
                'scope' => $this->scope,
                'period_from' => $this->period_from,
                'period_to' => $this->period_to,
                'created_by_id' => auth()->user()->id,
                'created_by_type' => get_class(auth()->user()),
                'status' => 'pending',
                'origin' => 'manual'
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
        return view('livewire.audit.business.add-modal');
    }
}
