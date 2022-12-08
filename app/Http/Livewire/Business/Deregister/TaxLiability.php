<?php

namespace App\Http\Livewire\Business\Deregister;

use Livewire\Component;
use App\Enum\LeaseStatus;
use App\Models\BusinessDeregistration;
use App\Models\LandLeaseDebt;
use App\Models\Returns\TaxReturn;
use App\Models\TaxAudit\TaxAudit;
use App\Models\Returns\ReturnStatus;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\Verification\TaxVerification;
use App\Models\Investigation\TaxInvestigation;

class TaxLiability extends Component
{

    public $business_id;
    public $location_id;
    public $b_id;

    public $return_debts;
    public $audit_debts;
    public $investigation_debts;
    public $verification_debts;
    public $land_lease_debts;

    public $can_initiate_audit = false;
    public $deregister_id;
    public $deregister;

    public function mount($business_id, $location_id, $deregister_id)
    {
        $this->business_id = $business_id;
        $this->location_id = $location_id;
        $this->deregister_id = $deregister_id;
        $this->deregister = BusinessDeregistration::findOrFail($deregister_id);

        $this->return_debts = TaxReturn::where('business_id', $this->business_id)
            ->OrWhere('location_id', $this->location_id)
            ->where('payment_status', '!=', ReturnStatus::COMPLETE)
            ->with('installment')
            ->get();

        $this->land_lease_debts = LandLeaseDebt::where('business_location_id', $this->location_id)
            ->where('status', '!=', LeaseStatus::COMPLETE)
            ->get();

        $this->investigation_debts = TaxAssessment::where('business_id', $this->business_id)
            ->OrWhere('location_id', $this->location_id)
            ->where('assessment_type', TaxInvestigation::class)
            ->where('payment_status', '!=', ReturnStatus::COMPLETE)
            ->get();

        $this->audit_debts = TaxAssessment::where('business_id', $this->business_id)
            ->OrWhere('location_id', $this->location_id)
            ->where('assessment_type', TaxAudit::class)
            ->where('payment_status', '!=', ReturnStatus::COMPLETE)
            ->get();

        $this->verification_debts = TaxAssessment::where('business_id', $this->business_id)
            ->OrWhere('location_id', $this->location_id)
            ->where('assessment_type', TaxVerification::class)
            ->where('payment_status', '!=', ReturnStatus::COMPLETE)
            ->get();

        $check = TaxAudit::where('business_id', $this->business_id)
            ->where('location_id', $this->location_id ?? 0)
            ->whereIn('status', ['draft', 'pending'])
            ->get();

        if (($this->return_debts->count() > 0 || $this->land_lease_debts->count() > 0 || $this->investigation_debts->count() > 0 || $this->audit_debts->count() > 0 || $this->verification_debts->count() > 0) && $check->count() === 0) {
            $this->can_initiate_audit = true;
        }
    }

    public function render()
    {
        return view('livewire.business.deregister.tax-liability');
    }
}
