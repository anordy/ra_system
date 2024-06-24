<?php


namespace App\Http\Livewire\Audit;

use App\Enum\TaxAuditStatus;
use App\Models\TaxAudit\TaxAudit;
use App\Models\Investigation\TaxInvestigation;
use App\Models\Investigation\TaxInvestigationLocation;
use App\Models\Investigation\TaxInvestigationTaxType;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Exception;

class ForwardToInvestigation extends Component
{
    use CustomAlert;

    public $tax_audit_id;
    public $taxAudit;
    public $showModal = false;
    public $showButton = true;

    /**
     * Mounts the component with the provided TaxAudit model instance.
     * If the tax audit has already been forwarded to investigation or has been approved, the show button flag is set to false.
     *
     * @param TaxAudit $taxAudit The tax audit model instance to mount the component with.
     */
    public function mount(TaxAudit $taxAudit)
    {
        $this->taxAudit = $taxAudit;

        if ($taxAudit->forwarded_to_investigation || $taxAudit->status == TaxAuditStatus::APPROVED) {
            $this->showButton = false;
        }
    }


    public function forward()
    {
        DB::beginTransaction();
        try {
            $taxInvestigation = TaxInvestigation::create([
                'case_number' => TaxInvestigation::generateNewCaseNumber(),
                'business_id' => $this->taxAudit->business_id,
                'location_id' => count($this->taxAudit->taxAuditLocations) <= 1 ? $this->taxAudit->taxAuditLocations->first()->id : 0,
                'tax_type_id' => count($this->taxAudit->taxTypes) <= 1 ? $this->taxAudit->taxTypes->first()->id : 0,
                'intension' => $this->taxAudit->intension,
                'scope' => $this->taxAudit->scope,
                'period_from' => $this->taxAudit->period_from,
                'period_to' => $this->taxAudit->period_to,
                'created_by_id' => auth()->user()->id,
                'created_by_type' => get_class(auth()->user()),
                'status' => 'draft',
                'origin' => 'manual'
            ]);

            foreach ($this->taxAudit->taxAuditLocations as $location) {
                TaxInvestigationLocation::create([
                    'tax_investigation_id' => $taxInvestigation->id,
                    'business_location_id' => $location->business_location_id
                ]);
            }

            foreach ($this->taxAudit->taxTypes as $taxType) {
                TaxInvestigationTaxType::create([
                    'tax_investigation_id' => $taxInvestigation->id,
                    'business_tax_type_id' => $taxType->id
                ]);
            }

            DB::commit();
            $this->customAlert('success', 'Tax audit forwarded to investigation successfully');
            $this->showModal = false;
            $this->showButton = false;
            $this->taxAudit->forwarded_to_investigation = true;
            $this->taxAudit->save();

            //TODO: redirect to the investigation page
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->showModal = false;
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }
    protected $listeners = [
        'forward', 'reject'
    ];

    public function confirmPopUpModal($action)
    {
        $this->customAlert('warning', 'Are you sure you want to foward to investigation', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Confirm',
            'onConfirmed' => $action,
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'transition' => $action
            ],

        ]);
    }

    public function render()
    {
        return view('livewire.audit.forward-to-investigation', [
            'taxAudit' => $this->taxAudit,
        ]);
    }
}
