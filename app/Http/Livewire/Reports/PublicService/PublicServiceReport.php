<?php

namespace App\Http\Livewire\Reports\PublicService;

use App\Enum\ReportStatus;
use App\Models\FinancialYear;
use App\Models\MvrRegistrationType;
use App\Traits\CustomAlert;
use App\Traits\PublicServiceReportTrait;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class PublicServiceReport extends Component
{
    use CustomAlert, PublicServiceReportTrait;

    public $optionYears;
    public $optionPeriods;
    public $optionSemiAnnuals;
    public $optionQuarters;
    public $optionMonths;
    public $optionReportTypes;
    public $optionRegTypes;
    public $optionPaymentStatus;

    public $year;
    public $month;
    public $period;
    public $quater;
    public $semiAnnual;
    public $report_type, $registration_type, $payment_type;
    public $filing_report_type;
    public $payment_report_type;
    public $startMonth;
    public $endMonth;
    public $range_start;
    public $range_end;
    public $today;
    public $regTypeName = ReportStatus::PS_REG_REPORT;
    public $paymentTypeName = ReportStatus::PS_PAYMENT_REPORT;
    public $filter_type = 'custom';

    protected function rules()
    {
        return [
            'report_type' => 'required|string',
            'filter_type' => 'required|string',
            'registration_type' => ['required_if:report_type,' . $this->regTypeName],
            'payment_type' => ['required_if:report_type,' . $this->paymentTypeName],
            'year' => 'nullable|string',
            'period' => $this->year != ReportStatus::all ? 'required_if:filter_type,'.ReportStatus::YEARLY : 'nullable',
            'month' => $this->period == ReportStatus::MONTHLY ? 'required' : 'nullable',
            'quater' => $this->period == ReportStatus::QUARTERLY ? 'required' : 'nullable',
            'semiAnnual' => $this->period == ReportStatus::SEMI_ANNUAL ? 'required' : 'nullable',
            'range_start' => 'required|date',
            'range_end' => 'required|date',
        ];
    }

    public function mount()
    {
        $this->today = date('Y-m-d');
        $this->range_start = $this->today;
        $this->range_end = $this->today;
        $this->optionYears = FinancialYear::pluck('code');
        $this->optionReportTypes = [$this->paymentTypeName, $this->regTypeName];
        $this->optionPaymentStatus = [ReportStatus::All, ReportStatus::PAID, ReportStatus::UNPAID];
        $this->optionPeriods = [ReportStatus::MONTHLY, ReportStatus::QUARTERLY, ReportStatus::SEMI_ANNUAL, ReportStatus::ANNUAL];
        $this->optionSemiAnnuals = [ReportStatus::FIRST_SEMI_ANNUAL, ReportStatus::SECOND_SEMI_ANNUAL];
        $this->optionQuarters = [ReportStatus::FIRST_QUARTER, ReportStatus::SECOND_QUARTER, ReportStatus::THIRD_QUARTER, ReportStatus::FOURTH_QUARTER];
        $this->optionMonths = ReportStatus::MONTHS_DESC;
    }

    public function updated($propertyName)
    {
        if ($propertyName == 'period') {
            $this->reset('month', 'quater', 'semiAnnual');
        }
        if ($propertyName == 'year') {
            $this->reset('month', 'quater', 'semiAnnual', 'period');
        }

        if ($propertyName == 'report_type') {
            $this->reset('registration_type', 'payment_type');
            if ($this->report_type == $this->regTypeName) {
                $this->optionRegTypes = MvrRegistrationType::query()
                    ->whereIn('name', [
                        MvrRegistrationType::TYPE_COMMERCIAL_TAXI,
                        MvrRegistrationType::TYPE_COMMERCIAL_PRIVATE_HIRE,
                        MvrRegistrationType::TYPE_COMMERCIAL_GOODS_VEHICLE,
                        MvrRegistrationType::TYPE_COMMERCIAL_STAFF_BUS,
                        MvrRegistrationType::TYPE_COMMERCIAL_SCHOOL_BUS,
                    ])->select('id', 'name')->get();
            }
        }
    }

    public function exportExcel()
    {
        $this->validate();
        $parameters = $this->getParameters();
        $records = $this->getRecords($parameters);
        if ($records->count() < 1) {
            $this->customAlert('error', 'No Records Found in the selected criteria');
            return;
        }

        if ($parameters['report_type'] == ReportStatus::all) {
            $report_type = ReportStatus::All;
        } else {
            $report_type = $this->report_type;
        }

        if ($parameters['year'] == ReportStatus::all) {
            $fileName = "{$report_type}.xlsx";
            $title = "Public Service Report for {$report_type}";
        } else {
            $fileName = "{$report_type}-{$parameters['year']}.xlsx";
            $title = "Public Service Report for {$report_type}-{$parameters['year']}";
        }

        $this->customAlert('success', 'Exporting Excel File');

        if ($parameters['report_type'] == $this->paymentTypeName) {
            return Excel::download(new \App\Exports\PublicService\PaymentReportExport($records, $title, $parameters), $fileName);
        } else if ($parameters['report_type'] == $this->regTypeName) {
            return Excel::download(new \App\Exports\PublicService\RegistrationReportExport($records, $title, $parameters), $fileName);
        } else {
            $this->customAlert('warning', 'Invalid Report Type Selected');
            return;
        }

    }

    public function exportPdf()
    {
        $this->validate();
        $parameters = $this->getParameters();

        $records = $this->getRecords($parameters);
        if ($records->count() < 1) {
            $this->customAlert('error', 'No Records Found in the selected criteria');
            return;
        }

        $this->customAlert('success', 'Exporting Pdf File');

        if ($parameters['report_type'] == $this->paymentTypeName) {
            return redirect()->route('reports.public-service.payment.pdf', encrypt(json_encode($parameters)));
        } else if ($parameters['report_type'] == $this->regTypeName) {
            return redirect()->route('reports.public-service.registration.pdf', encrypt(json_encode($parameters)));
        } else {
            $this->customAlert('warning', 'Invalid Report Type Selected');
            return;
        }
    }


    public function getParameters()
    {
        return [
            'report_type' => $this->report_type,
            'payment_type' => $this->payment_type,
            'reg_type' => $this->registration_type,
            'year' => $this->year,
            'period' => $this->period,
            'month' => $this->month,
            'quater' => $this->quater,
            'semiAnnual' => $this->semiAnnual,
            'dates' => $this->getStartEndDate(),
            'range_start' => $this->range_start,
            'range_end' => $this->range_end,
        ];
    }

    public function getStartEndDate()
    {
        if ($this->year == ReportStatus::all) {
            return [
                'startDate' => null,
                'endDate' => null,
            ];
        } elseif ($this->month) {
            $date = \Carbon\Carbon::parse($this->year . "-" . $this->month . "-01");
            $start = $date->startOfMonth()->format('Y-m-d H:i:s');
            $end = $date->endOfMonth()->format('Y-m-d H:i:s');
            $from = $date->startOfMonth()->format('Y-m-d');
            $to = $date->endOfMonth()->format('Y-m-d');
            return ['startDate' => $start, 'endDate' => $end, 'from' => $from, 'to' => $to];
        } elseif ($this->quater) {
            if ($this->quater == ReportStatus::FIRST_QUARTER) {
                $this->startMonth = 1;
                $this->endMonth = 3;
            } elseif ($this->quater == ReportStatus::SECOND_QUARTER) {
                $this->startMonth = 4;
                $this->endMonth = 6;
            } elseif ($this->quater == ReportStatus::THIRD_QUARTER) {
                $this->startMonth = 7;
                $this->endMonth = 9;
            } elseif ($this->quater == ReportStatus::FOURTH_QUARTER) {
                $this->startMonth = 10;
                $this->endMonth = 12;
            }

            $startDate = \Carbon\Carbon::parse($this->year . "-" . $this->startMonth . "-01");
            $endDate = \Carbon\Carbon::parse($this->year . "-" . $this->endMonth . "-01");
            $start = $startDate->startOfMonth()->format('Y-m-d H:i:s');
            $end = $endDate->endOfMonth()->format('Y-m-d H:i:s');
            $from = $startDate->format('Y-m-d');
            $to = $endDate->format('Y-m-d');
            return ['startDate' => $start, 'endDate' => $end, 'from' => $from, 'to' => $to];
        } elseif ($this->semiAnnual) {
            if ($this->semiAnnual == ReportStatus::FIRST_SEMI_ANNUAL) {
                $this->startMonth = 1;
                $this->endMonth = 6;
            } elseif ($this->semiAnnual == ReportStatus::SECOND_SEMI_ANNUAL) {
                $this->startMonth = 7;
                $this->endMonth = 12;
            }
            $startDate = \Carbon\Carbon::parse($this->year . "-" . $this->startMonth . "-01");
            $endDate = \Carbon\Carbon::parse($this->year . "-" . $this->endMonth . "-01");
            $start = $startDate->startOfMonth()->format('Y-m-d H:i:s');
            $end = $endDate->endOfMonth()->format('Y-m-d H:i:s');
            $from = $startDate->format('Y-m-d');
            $to = $endDate->format('Y-m-d');
            return ['startDate' => $start, 'endDate' => $end, 'from' => $from, 'to' => $to];
        } else {
            $startDate = \Carbon\Carbon::parse($this->year . "-" . "01" . "-01");
            $endDate = \Carbon\Carbon::parse($this->year . "-" . "12" . "-01");
            $start = $startDate->startOfMonth()->format('Y-m-d H:i:s');
            $end = $endDate->endOfMonth()->format('Y-m-d H:i:s');
            $from = $startDate->format('Y-m-d');
            $to = $endDate->format('Y-m-d');
            return ['startDate' => $start, 'endDate' => $end, 'from' => $from, 'to' => $to];
        }
    }

    public function render()
    {
        return view('livewire.reports.public-service.public-service-report');
    }
}
