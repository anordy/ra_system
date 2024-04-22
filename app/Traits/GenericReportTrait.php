<?php

namespace App\Traits;

use App\Enum\ReportStatus;
use App\Models\FinancialYear;

trait GenericReportTrait
{

    public function initializeOptions()
    {
        $this->optionReportTypes = ReportStatus::RETURN_REPORT_TYPES;

        $this->optionFilingTypes = ReportStatus::RETURN_FILLING_TYPES;

        $this->optionPaymentTypes = ReportStatus::RETURN_PAYMENT_TYPES;

        $this->optionVatTypes = ReportStatus::RETURN_VAT_OPTION_TYPES;

        $this->optionYears = FinancialYear::orderBy('code', 'DESC')->pluck('code');

        $this->optionPeriods = [ReportStatus::MONTHLY, ReportStatus::QUARTERLY, ReportStatus::SEMI_ANNUAL, ReportStatus::ANNUAL];

        $this->optionSemiAnnuals = [ReportStatus::FIRST_SEMI_ANNUAL, ReportStatus::SECOND_SEMI_ANNUAL];

        $this->optionQuarters = [ReportStatus::FIRST_QUARTER, ReportStatus::SECOND_QUARTER, ReportStatus::THIRD_QUARTER, ReportStatus::FOURTH_QUARTER];

        $this->optionMonths = ReportStatus::MONTHS_DESC;
    }

}
