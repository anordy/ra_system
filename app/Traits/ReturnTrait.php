<?php

namespace App\Traits;

use App\Enum\ReturnApplicationStatus;
use App\Models\Returns\ReturnStatus;

trait ReturnTrait{
    public function canBeEdited(){
        return false; // No return can be edited at the moment
        if ($this->application_status === ReturnApplicationStatus::ADJUSTED){
            return false;
        }
        if (\Carbon\Carbon::now()->greaterThan($this->financialMonth->due_date)){
            return false;
        }
        if ($this->status === ReturnStatus::ON_CLAIM){
            return false;
        }

        $adjusted = self::where('financial_month_id', $this->financial_month_id)->where('application_status', ReturnApplicationStatus::ADJUSTED)->count();
        if ($adjusted){
            return false;
        }

        return true;
    }

    public function getApplicationStatus(){
        switch ($this->application_status) {
            case ReturnApplicationStatus::SELF_ASSESSMENT:
                return 'Self Assessment';
            default:
                return $this->application_status ?: 'N/A';
        }
    }

    public function getPaymentStatus(){
        switch ($this->status) {
            case ReturnStatus::SUBMITTED;
                return 'Payments not initiated.';
            case ReturnStatus::CN_GENERATED:
                return 'Waiting for payment.';
            case ReturnStatus::COMPLETE:
                return 'Paid.';
            case ReturnStatus::ON_CLAIM:
                return 'On claim.';
            default:
                return $this->status;
        }
    }
}
