<?php

namespace App\Http\Livewire\Audit;

use App\Enum\TaxAuditStatus;
use App\Models\Business;
use App\Models\TaxAudit\TaxAudit;
use App\Models\TaxAudit\TaxAuditLocation;
use App\Models\TaxAudit\TaxAuditTaxType;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class BusinessAuditAddModal extends Component
{

    use CustomAlert;

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
            'business_id' => 'required|numeric|exists:businesses,id',
            'location_ids' => 'required',
            'location_ids.*' => 'required|numeric',
            'tax_type_ids' => 'required',
            'tax_type_ids.*' => 'required|numeric',
            'intension' => 'required|strip_tag',
            'scope' => 'required|strip_tag',
            'period_from' => 'required|date',
            'period_to' => 'required|date|after:period_from',
        ];
    }

    public function mount($jsonData = null)
    {
        $this->business = Business::select('id', 'name')->get();

        if (isset($jsonData)) {
            $this->business_id = $jsonData['business_id'];
            $this->businessChange($this->business_id);
            $this->location_ids[] = $jsonData['location_ids'];
        }
    }

    public function businessChange($id)
    {
        if ($this->business_id) {
            $this->selectedBusiness = Business::with('locations', 'taxTypes')->find($id);
            if (is_null($this->selectedBusiness)) {
                $this->customAlert('warning', 'The selected business location does not exist');
                return;
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

        $check = TaxAudit::where('business_id', $this->business_id)
            ->whereHas('taxAuditLocations', function ($query) use ($location_ids) {
                $query->whereIn('business_location_id', $location_ids);
            })
            ->whereHas('taxAuditTaxTypes', function ($query) use ($tax_type_ids) {
                $query->whereIn('business_tax_type_id', $tax_type_ids);
            })
            ->whereIn('status', [TaxAuditStatus::DRAFT, TaxAuditStatus::PENDING])
            ->first();

        if ($check) {
            $this->customAlert('warning', 'The selected business location and tax type is already on auditing');
            return;
        }

        DB::beginTransaction();
        try {
            $taxAudit = TaxAudit::create([
                'business_id' => $this->business_id,
                'location_id' => count($this->location_ids) <= 1 ? $this->location_ids[0] : 0,
                'tax_type_id' => count($this->tax_type_ids) <= 1 ? $this->tax_type_ids[0] : 0,
                'intension' => $this->intension,
                'scope' => $this->scope,
                'period_from' => $this->period_from,
                'period_to' => $this->period_to,
                'created_by_id' => auth()->user()->id,
                'created_by_type' => get_class(auth()->user()),
                'status' => TaxAuditStatus::DRAFT,
                'origin' => 'manual'
            ]);

            //TODO: check if this is correct or not

            // Check if TaxAudit creation was successful
            if (!$taxAudit) {
                throw new Exception('Failed to create Tax Audit record');
            }

            foreach ($this->location_ids as $location_id) {
                $taxAuditLocation = TaxAuditLocation::create([
                    'tax_audit_id' => $taxAudit->id,
                    'business_location_id' => $location_id
                ]);

                // Check if TaxAuditLocation creation was successful
                if (!$taxAuditLocation) {
                    throw new Exception('Failed to create Tax Audit Location record');
                }
            }

            foreach ($this->tax_type_ids as $tax_type_id) {
                $taxAuditTaxType = TaxAuditTaxType::create([
                    'tax_audit_id' => $taxAudit->id,
                    'business_tax_type_id' => $tax_type_id
                ]);

                // Check if TaxAuditTaxType creation was successful
                if (!$taxAuditTaxType) {
                    throw new Exception('Failed to create Tax Audit Tax Type record');
                }
            }

            DB::commit();
            $this->customAlert('success', 'Business added to Auditing successfully');
            return redirect()->route('tax_auditing.approvals.index');
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
        return view('livewire.audit.business.add-modal');
    }
}
