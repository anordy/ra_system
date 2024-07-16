<?php

namespace App\Http\Livewire\Audit;

use App\Enum\GeneralConstant;
use App\Enum\TaxAuditStatus;
use App\Models\BusinessLocation;
use App\Models\MvrPlateNumberStatus;
use App\Models\TaxAudit\TaxAudit;
use App\Models\TaxAudit\TaxAuditLocation;
use App\Models\TaxAudit\TaxAuditTaxType;
use App\Traits\CustomAlert;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class BusinessWithRiskTable extends Component
{
    use CustomAlert, WithPagination;

    public $paginationTheme = 'bootstrap';
    public $perPage = 15;
    public $searchQuery = '';

    public $sortBy = 'created_at';
    public $sortDirection = 'DESC';

    private $searchableColumns = [
        'name',
        'zin',
        'business.tin',
        'business.name'
    ];
    public $selectedItems = [];

    protected $listeners = [
        'addToAuditBulk'
    ];

    public function addToAuditBulk()
    {
        try {
            $locationsIds = array_keys($this->selectedItems, true);

            $check = TaxAudit::query()
                ->whereHas('taxAuditLocations', function ($query) use ($locationsIds) {
                    $query->whereIn('business_location_id', $locationsIds);
                })
                ->whereIn('status', [TaxAuditStatus::DRAFT, TaxAuditStatus::PENDING])
                ->get();

            if ($check->count()) {
                $names = $check->pluck('location.name')->implode(', ');
                $this->customAlert('warning', "Business with name(s) $names already exist on auditing");
                return;
            }

            DB::beginTransaction();
            foreach ($locationsIds as $locationId) {
                // get location
                $location = BusinessLocation::find($locationId);
                $business = $location->business;

                // create audit
                $taxAudit = TaxAudit::create([
                    'business_id' => $business->id,
                    'location_id' => $locationsIds[0],
                    'tax_type_id' => $business->taxTypes->first()->id,
                    'created_by_id' => auth()->user()->id,
                    'created_by_type' => get_class(auth()->user()),
                    'status' => TaxAuditStatus::DRAFT,
                    'origin' => 'manual'
                ]);

                if (!$taxAudit) {
                    throw new Exception('Failed to create Tax Audit record');
                }

                foreach ($business->locations as $location) {
                    $taxAuditLocation = TaxAuditLocation::create([
                        'tax_audit_id' => $taxAudit->id,
                        'business_location_id' => $location->id
                    ]);

                    // Check if TaxAuditLocation creation was successful
                    if (!$taxAuditLocation) {
                        throw new Exception('Failed to create Tax Audit Location record');
                    }
                }

                foreach ($business->taxTypes as $taxType) {
                    $taxAuditTaxType = TaxAuditTaxType::create([
                        'tax_audit_id' => $taxAudit->id,
                        'business_tax_type_id' => $taxType->id
                    ]);

                    // Check if TaxAuditTaxType creation was successful
                    if (!$taxAuditTaxType) {
                        throw new Exception('Failed to create Tax Audit Tax Type record');
                    }
                }
            }
            DB::commit();

            session()->flash('success', 'Selected tax types added to tax audit.');
            return redirect()->route('tax_auditing.approvals.index');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('ADD-TO-AUDIT-BULK-ERROR', [$e]);
            $this->customAlert(GeneralConstant::WARNING, 'Something went wrong, please contact the administrator for help');
        }
    }

    public function updateToPrinted($id)
    {
        $this->customAlert(GeneralConstant::QUESTION, 'Update Status to <span class="text-uppercase font-weight-bold">Printed</span>?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Yes',
            'onConfirmed' => 'confirmUpdate',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $id,
                'status' => MvrPlateNumberStatus::STATUS_PRINTED
            ],
        ]);
    }

    public function confirmAddToAudit()
    {
        if (count($this->selectedItems) <= 0) {
            $this->customAlert(GeneralConstant::ERROR, 'Please select at least one business to continue.');
            return;
        }

        $this->customConfirm('Are you sure you want to add selected businesses to auditing ?', 'addToAuditBulk', []);
    }

    public function scopeSearch($query, $searchTerm, array $columns)
    {
        return $query->where(function ($q) use ($searchTerm, $columns) {
            foreach ($columns as $column) {
                if (str_contains($column, '.')) {
                    // This is a relation
                    list($relation, $relatedColumn) = explode('.', $column, 2);
                    $q->orWhereHas($relation, function ($subQ) use ($searchTerm, $relatedColumn) {
                        $subQ->whereRaw("LOWER($relatedColumn) LIKE ?", ['%' . strtolower($searchTerm) . '%']);
                    });
                } else {
                    // This is a local column
                    $q->orWhereRaw("LOWER($column) LIKE ?", ['%' . strtolower($searchTerm) . '%']);
                }
            }
        });
    }

    public function setSortBy($sortByField){
        if ($this->sortBy == $sortByField) {
            $this->sortDirection = $this->sortDirection == 'ASC' ? 'DESC' : 'ASC';
            return;
        }

        $this->sortBy = $sortByField;
        $this->sortDirection = 'DESC';
    }

    public function render()
    {
        $query = BusinessLocation::whereHas('taxVerifications', function ($query) {
            $query->whereHas('riskIndicators');
        })->with('taxVerifications.riskIndicators', 'business');

        $query = $query->orderBy($this->sortBy, $this->sortDirection);

        $query = $this->scopeSearch($query, $this->searchQuery, $this->searchableColumns);

        return view('livewire.audit.business-with-risk-table', [
            'locations' => $query
                ->latest()
                ->paginate($this->perPage),
        ]);
    }
}
