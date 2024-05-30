<?php

namespace App\Traits;

use App\Models\Assessment;
use App\Models\BillStatus;
use App\Models\Currency;
use App\Models\FinancialYear;
use App\Models\PropertyPayment;
use App\Models\PropertyPaymentCategoryStatus;
use App\Models\PropertyStatus;
use App\Models\ReturnStatus;
use App\Models\Role;
use App\Models\TaxAssessment;
use App\Models\TaxType;
use App\Models\User;
use App\Models\ZmBill;
use App\Models\ZmCore;
use App\Models\ZmResponse;
use App\Notifications\DatabaseNotification;
use Auth;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Str;

trait ApprovalProcessingTrait
{
    private function getSubject()
    {
        return app($this->modelName)->findOrFail($this->modelId);
    }

    private function formatDate($date)
    {
        return isNullOrEmpty($date) ? null : Carbon::create($date)->format('Y-m-d');
    }

    private function initializeAssessment()
    {
        $assessment = $this->subject->assessment;
        $this->hasAssessment = $assessment ? "1" : "0";

        if ($assessment) {
            $this->taxAssessments = TaxAssessment::where('assessment_id', $this->subject->id)
                ->where('assessment_type', get_class($this->subject))
                ->get();
        }
    }

    private function initializeTaxTypeAmounts()
    {
        $taxTypes = $this->investigation->InvestigationTaxType();

        foreach ($taxTypes as $taxType) {
            $taxTypeKey = str_replace(' ', '_', $taxType['name']);
            $this->principalAmounts[$taxTypeKey] = null;
            $this->interestAmounts[$taxTypeKey] = null;
            $this->penaltyAmounts[$taxTypeKey] = null;
            $this->taxTypeIds[$taxTypeKey] = $taxType['id'];
        }
    }

    private function initializeStaffAndRoles()
    {
        if ($this->task != null) {
            $operators = json_decode($this->task->operators, true) ?: [];
            $roles = Role::whereIn('id', $operators)->get()->pluck('id')->toArray();
            $this->subRoles = Role::whereIn('report_to', $roles)->get();
            $this->staffs = User::whereIn('role_id', $this->subRoles->pluck('id')->toArray())->get();
        }
    }

    private function generateGeneralControlNumber(ZmBill $zmBill)
    {
        $zmBill->generateControlNumber();
        $zmBill->save();

        if ($zmBill->zan_trx_sts_code == ZmResponse::SUCCESS) {
            $assessment = Assessment::findOrFail($zmBill->billable_id);
            $assessment->payment_status = ReturnStatus::CN_GENERATED;
            $assessment->save();
            $this->customAlert('success', 'A control number for this verification has been generated successfully');
        } else {
            $this->customAlert('error', 'Failed to generate control number, please try again later');
        }
    }

    private function getExchangeRate($currency)
    {
        return 1;
    }

    private function generateURN($property)
    {
        $urn = Str::upper(Str::random(6));
        $existingUrn = $property->whereUrn($urn)->first();

        if ($existingUrn) {
            return $this->generateURN($property);
        }

        return $urn;
    }

    private function getPayableAmount($property)
    {
        return $property->rate * $property->area;
    }
}
