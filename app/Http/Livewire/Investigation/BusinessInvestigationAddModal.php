<?php

namespace App\Http\Livewire\Investigation;

use App\Models\Business;
use App\Models\FinancialYear;
use App\Models\Investigation\TaxInvestigation;
use App\Models\Investigation\TaxInvestigationLocation;
use App\Models\Investigation\TaxInvestigationTaxType;
use App\Models\TaxAudit\TaxAudit;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class BusinessInvestigationAddModal extends Component
{

    use CustomAlert;

    public $query;
    public $highlightIndex;
    public $name;
    public $description;
    public $business;
    public $business_id;
    public $location_ids;
    public $tax_type_ids;
    public $allegations;
    public $descriptions;
    public $period_from;
    public $period_to;

    public $selectedBusiness;
    public $locations = [];
    public $taxTypes = [];


    protected function rules()
    {
        return [
            'business_id' => 'required|numeric|exists:businesses,id',
            'location_ids' => 'required',
            'location_ids.*' => 'required|numeric',
            'tax_type_ids' => 'required',
            'tax_type_ids.*' => 'required|numeric',
            'allegations' => 'required|strip_tag|string',
            'descriptions' => 'required|strip_tag|string',
            'period_from' => 'required|date|after:businesses,created_at',
            'period_to' => 'required|date|after:period_from',
        ];
    }



    public function resetFields() {
        $this->query = '';
        $this->business = [];
        $this->highlightIndex = 0;
    }

    public function incrementHighlight() {
        if ($this->highlightIndex === count($this->business) - 1) {
            $this->highlightIndex = 0;
            return;
        }
        $this->highlightIndex++;
    }

    public function decrementHighlight() {
        if ($this->highlightIndex === 0) {
            $this->highlightIndex = count($this->business) - 1;
            return;
        }
        $this->highlightIndex--;
    }

    public function updatedQuery() {
        $this->business = Business::query()->select('id', 'name', 'ztn_number')
            ->whereRaw('LOWER(name) LIKE ?', [ '%' . strtolower($this->query) . '%'])
            ->orWhereRaw('LOWER(ztn_number) LIKE ?', [ '%' . strtolower($this->query) . '%'])
            ->get()
            ->toArray();
    }

    public function mount($jsonData = null)
    {
        $this->resetFields();
        if (isset($jsonData)) {
            $this->business_id = $jsonData['business_id'];
            $this->businessChange($this->business_id);
            $this->location_ids[] = $jsonData['location_ids'];
        }
    }


    public function selectBusiness() {
        $this->selectedBusiness = $this->contacts[$this->highlightIndex] ?? null;
    }

    public function businessChange($id)
    {
        if ($this->business_id) {
            $this->selectedBusiness = Business::with('locations')->find($id);
            if (is_null($this->selectedBusiness)) {
                abort(404);
            }
            $this->taxTypes         = $this->selectedBusiness->taxTypes;
            $this->locations        = $this->selectedBusiness->locations;
        } else {
            $this->reset('taxTypes', 'locations');
        }
    }


    public function submit()
    {

        $this->validate();
        $location_ids = $this->location_ids;
        $tax_type_ids = $this->tax_type_ids;

        $check = TaxInvestigation::where('business_id', $this->business_id)
            ->whereHas('taxInvestigationLocations', function ($query) use ($location_ids) {
                $query->whereIn('business_location_id', $location_ids);
            })
            ->whereHas('taxInvestigationTaxTypes', function ($query) use ($tax_type_ids) {
                $query->whereIn('business_tax_type_id', $tax_type_ids);
            })
            ->whereIn('status', ['draft', 'pending'])
            ->first();

        if ($check) {
            $this->customAlert('warning', 'The selected business location and tax type is already on investigation');
            return;
        }

        DB::beginTransaction();
        try {
            $taxInvestigation = TaxInvestigation::create([
                'case_number' => TaxInvestigation::generateNewCaseNumber(),
                'business_id' => $this->business_id,
                'location_id' => count($this->location_ids) <= 1 ? $this->location_ids[0] : 0,
                'tax_type_id' => count($this->tax_type_ids) <= 1 ? $this->tax_type_ids[0] : 0,
                'intension' => $this->allegations,
                'scope' => $this->descriptions,
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
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }


    public function render()
    {
        return view('livewire.investigation.business.add-modal');
    }
}
