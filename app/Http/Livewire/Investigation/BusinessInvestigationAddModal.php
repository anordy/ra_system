<?php

namespace App\Http\Livewire\Investigation;

use App\Models\Business;
use App\Models\Investigation\TaxInvestigation;
use App\Models\Investigation\TaxInvestigationLocation;
use App\Models\Investigation\TaxInvestigationTaxType;
use Exception;
use Illuminate\Support\Facades\DB;
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
    public $location_ids;
    public $tax_type_ids;
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
            'location_ids' => 'required',
            'tax_type_ids' => 'required',
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
        $check = TaxInvestigation::where('business_id', $this->business_id)
            ->where('location_id', $this->location_ids)
            ->where('tax_type_id', $this->tax_type_ids)
            ->whereIn('status', ['draft', 'pending'])
            ->first();

        if ($check) {
            $this->validate(
                ['business_id' => 'required|email'],
                ['business_id.email' => 'Business with the given tax type is already on investigationing']
            );
        }

        $this->validate();
        DB::beginTransaction();
        try {
            $taxInvestigation = TaxInvestigation::create([
                'business_id' => $this->business_id,
                'location_id' => count($this->location_ids) <= 1 ? $this->location_ids[0] : 0,
                'tax_type_id' => count($this->tax_type_ids) <= 1 ? $this->tax_type_ids[0] : 0,
                'intension' => $this->intension,
                'scope' => $this->scope,
                'period_from' => $this->period_from,
                'period_to' => $this->period_to,
                'created_by_id' => auth()->user()->id,
                'created_by_type' => get_class(auth()->user()),
                'status' => 'draft',
                'origin' => 'manual'
            ]);

            foreach ($this->location_ids as $location_id) {
                
                TaxInvestigationLocation::create([
                    'tax_investigation_id' => $taxInvestigation->id,
                    'business_location_id' => $location_id
                ]);
            }

            foreach ($this->tax_type_ids as $tax_type_id) {
                
                TaxInvestigationTaxType::create([
                    'tax_investigation_id' => $taxInvestigation->id,
                    'business_tax_type_id' => $tax_type_id
                ]);
            }

            DB::commit();
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            $this->alert('error', 'Something went wrong, please contact our support desk for help');
        }
    }

    public function render()
    {
        return view('livewire.investigation.business.add-modal');
    }
}
