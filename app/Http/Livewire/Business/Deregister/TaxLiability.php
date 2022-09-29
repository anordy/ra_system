<?php

namespace App\Http\Livewire\Business\Deregister;

use Livewire\Component;
use App\Enum\LeaseStatus;
use App\Enum\ReturnCategory;
use App\Models\LandLeaseDebt;
use App\Models\Returns\TaxReturn;
use App\Models\TaxAudit\TaxAudit;
use App\Models\Returns\ReturnStatus;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\Verification\TaxVerification;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Investigation\TaxInvestigation;

class TaxLiability extends Component
{

    use LivewireAlert;

    public $business_id;
    public $location_id;

    public $return_debts;
    public $audit_debts;
    public $investigation_debts;
    public $verification_debts;
    public $land_lease_debts;

    public function mount($business_id, $location_id)
    {
        $this->business_id = $business_id;
        $this->location_id = $location_id;

        $this->return_debts = TaxReturn::where('business_id', $this->business_id)
            ->OrWhere('location_id', $this->location_id)
            ->whereIn('return_category', [ReturnCategory::DEBT, ReturnCategory::OVERDUE])
            ->where('payment_status', '!=', ReturnStatus::COMPLETE)
            ->with('installment')
            ->get();

        $this->land_lease_debts = LandLeaseDebt::where('business_location_id', $this->location_id)
            ->where('status', LeaseStatus::PENDING)
            ->get();

        $this->investigation_debts = TaxAssessment::where('business_id', $this->business_id)
            ->OrWhere('location_id', $this->location_id)
            ->where('assessment_type', TaxInvestigation::class)
            ->whereIn('assessment_step', [ReturnCategory::DEBT, ReturnCategory::OVERDUE])
            ->get();

        $this->audit_debts = TaxAssessment::where('business_id', $this->business_id)
            ->OrWhere('location_id', $this->location_id)
            ->where('assessment_type', TaxAudit::class)
            ->whereIn('assessment_step', [ReturnCategory::DEBT, ReturnCategory::OVERDUE])
            ->get();

        $this->verification_debts = TaxAssessment::where('business_id', $this->business_id)
            ->OrWhere('location_id', $this->location_id)
            ->where('assessment_type', TaxVerification::class)
            ->whereIn('assessment_step', [ReturnCategory::DEBT, ReturnCategory::OVERDUE])
            ->get();
            
    }

    public function render()
    {
        return view('livewire.business.deregister.tax-liability');
    }
}
