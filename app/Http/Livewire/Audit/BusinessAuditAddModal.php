<?php

namespace App\Http\Livewire\Audit;

use App\Enum\TaxAuditStatus;
use App\Models\Business;
use App\Models\BusinessStatus;
use App\Models\TaxAudit\TaxAudit;
use App\Models\TaxAudit\TaxAuditLocation;
use App\Models\TaxAudit\TaxAuditTaxType;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Illuminate\Validation\Rule;
use Livewire\Component;

class BusinessAuditAddModal extends Component
{

    use CustomAlert;

    public $name;
    public $description;
    public $business;
    public $business_id;
    public $location_ids = [];
    public $tax_type_ids = [];
    public $intension;
    public $scope;
    public $period_from;
    public $period_to;
    public $selectedBusiness;
    public $locations = [];
    public $taxTypes = [];
    public $search = '';
    public $ituDepartment = false;


    protected function rules()
    {
        return [
            'business_id' => 'required|numeric|exists:businesses,id',
            'location_ids' => [Rule::requiredIf(!$this->ituDepartment)],
            'location_ids.*' => [Rule::requiredIf(!$this->ituDepartment), 'numeric', 'exists:business_locations,id'],
            'tax_type_ids' => [Rule::requiredIf(!$this->ituDepartment)],
            'tax_type_ids.*' => [Rule::requiredIf(!$this->ituDepartment), 'numeric', 'exists:tax_types,id'],
            'intension' => [Rule::requiredIf(!$this->ituDepartment), 'strip_tag'],
            'scope' => [Rule::requiredIf(!$this->ituDepartment), 'strip_tag'],
            'period_from' => [Rule::requiredIf(!$this->ituDepartment), 'strip_tag'],
            'period_to' => [Rule::requiredIf(!$this->ituDepartment), 'strip_tag'],
        ];
    }

    public function mount($jsonData = null, $ituDepartment = false)
    {
        $this->business = Business::query()
            ->whereNotIn('status', [BusinessStatus::PENDING, BusinessStatus::DRAFT, BusinessStatus::REJECTED])
            ->select('id', 'name', 'reg_no', 'status')
            ->get();

        if (isset($jsonData) && $jsonData != null) {
            $this->business_id = $jsonData['business_id'];
            $this->businessChange($this->business_id);
            $this->location_ids[] = $jsonData['location_ids'];
        }

        $this->ituDepartment = $ituDepartment;
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

    public function searchBusiness()
    {
        $this->validate([
            'search' => 'required|string|max:255'
        ]);
        $this->search = trim($this->search);

        // Convert the search search to lowercase
        $searchTerm = strtolower($this->search);

        // Search for business using reg number, tin number, and name (case-insensitive)
        $business = Business::where(DB::raw('LOWER(reg_no)'), 'like', '%' . $searchTerm . '%')
            ->orWhere(DB::raw('LOWER(tin)'), 'like', '%' . $searchTerm . '%')
            ->orWhere(DB::raw('LOWER(name)'), 'like', '%' . $searchTerm . '%')
            ->get();


        // Change the selected business
        if (count($business) > 0) {
            $this->business_id = $business[0]->id;
            $this->businessChange($this->business_id);
        } else {
            $this->customAlert('warning', 'No business found with the given Search');
        }
    }


    public function submit()
    {
        $this->validate();

        if ($this->ituDepartment){
            $business = Business::with('locations', 'taxTypes')->find($this->business_id);
            $this->location_ids = $business->locations->pluck('id')->toArray();
            $this->tax_type_ids = $business->taxTypes->pluck('id')->toArray();
        }

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

        if (!isset($this->location_ids[0]) || !isset($this->tax_type_ids[0])) throw new Exception('Please provide at least one location or tax type');

        DB::beginTransaction();
        try {
            $taxAudit = TaxAudit::create([
                'business_id' => $this->business_id,
                'location_id' => $this->location_ids[0],
                'tax_type_id' => $this->tax_type_ids[0],
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
