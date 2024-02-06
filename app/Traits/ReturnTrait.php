<?php

namespace App\Traits;

use App\Enum\ReturnApplicationStatus;
use App\Models\Returns\ReturnStatus;

trait ReturnTrait{
    public function canBeEdited(): bool
    {
        return false; // No return can be edited at the moment
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
