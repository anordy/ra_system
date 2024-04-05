<?php

namespace App\Http\Livewire\Reports\Debts;

use App\Enum\CustomMessage;
use App\Enum\ReportStatus;
use App\Exports\Debts\AssessmentDebtReportExport;
use App\Exports\Debts\DebtReturnReportExport;
use App\Exports\Debts\DebtWaiverReportExport;
use App\Exports\Debts\DemandNoticeReportExport;
use App\Exports\Debts\InstallmentReportExport;
use App\Models\FinancialYear;
use App\Traits\CustomAlert;
use App\Traits\DebtReportTrait;
use App\Traits\GenericReportTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class DebtReport extends Component
{
    use CustomAlert, DebtReportTrait, GenericReportTrait;

    public $optionYears;
    public $optionPeriods;
    public $optionSemiAnnuals;
    public $optionQuarters;
    public $optionMonths;
    public $optionReportTypes;

    public $showPreviewTable = false;
    public $activateButtons = false;

    public $year;
    public $month;
    public $period;
    public $quater;
    public $semiAnnual;
    public $report_type;
    public $filing_report_type;
    public $payment_report_type;
    public $startMonth;
    public $endMonth;
    public $range_start;
    public $range_end;
    public $today;
    public $filter_type = 'custom';

    protected function rules()
    {
        return [
            'report_type' => 'required|alpha_gen',
            'filter_type' => 'required|alpha_gen',
            'year' => 'nullable|alpha_gen',
            'period' => $this->year != ReportStatus::all ? 'required_if:filter_type,yearly|alpha_gen' : 'nullable',
            'month' => $this->period == ReportStatus::MONTHLY ? ['required', 'digits_between:1,12'] : 'nullable',
            'quater' => $this->period == ReportStatus::QUARTERLY ? ['required', Rule::in([ReportStatus::FIRST_QUARTER, ReportStatus::SECOND_QUARTER, ReportStatus::THIRD_QUARTER, ReportStatus::FOURTH_QUARTER])] : '',
            'semiAnnual' => $this->period == ReportStatus::SEMI_ANNUAL ? 'required|alpha_gen' : 'nullable',
            'range_start' => 'required|date',
            'range_end' => 'required|date',
        ];
    }

    public function mount()
    {
        $this->today = date('Y-m-d');
        $this->range_start = $this->today;
        $this->range_end = $this->today;
        $this->initializeOptions();
        $this->optionReportTypes = [ReportStatus::RETURNS, ReportStatus::ASSESSMENTS, ReportStatus::WAIVER, ReportStatus::INSTALLMENT, ReportStatus::DEMAND_NOTICE];
    }

    public function updated($propertyName)
    {
        if ($propertyName == 'period') {
            $this->reset('month', 'quater', 'semiAnnual');
        }
        if ($propertyName == 'year') {
            $this->reset('month', 'quater', 'semiAnnual', 'period');
        }
    }

    public function exportExcel()
    {
        $this->validate();
        try {
            $parameters = $this->getParameters();
            $records = $this->getRecords($parameters);
            if ($records->count() < 1) {
                $this->customAlert('error', 'No Records Found in the selected criteria');
                return;
            }

            if (array_key_exists('report_type', $parameters) && $parameters['report_type'] == ReportStatus::all) {
                $report_type = ReportStatus::All;
            } else {
                $report_type = $this->report_type;
            }

            if (array_key_exists('year', $parameters) && $parameters['year'] == ReportStatus::all) {
                $fileName = "{$report_type}.xlsx";
                $title = "Debt Report for {$report_type}";
            } else {
                $fileName = "{$report_type}-{$parameters['year']}.xlsx";
                $title = "Debt Report for {$report_type}-{$parameters['year']}";
            }
            $this->customAlert('success', 'Exporting Excel File');

            if (!array_key_exists('report_type', $parameters)) {
                $this->customAlert('error', 'Missing Report Type Definition');
                return;
            }

            if ($parameters['report_type'] == ReportStatus::ASSESSMENTS) {
                return Excel::download(new AssessmentDebtReportExport($records, $title, $parameters), $fileName);
            } else if ($parameters['report_type'] == ReportStatus::RETURNS) {
                return Excel::download(new DebtReturnReportExport($records, $title, $parameters), $fileName);
            } else if ($parameters['report_type'] == ReportStatus::WAIVER) {
                return Excel::download(new DebtWaiverReportExport($records, $title, $parameters), $fileName);
            } else if ($parameters['report_type'] == ReportStatus::DEMAND_NOTICE) {
                return Excel::download(new DemandNoticeReportExport($records, $title, $parameters), $fileName);
            } else if ($parameters['report_type'] == ReportStatus::INSTALLMENT) {
                return Excel::download(new InstallmentReportExport($records, $title, $parameters), $fileName);
            }

        } catch (\Exception $exception) {
            Log::error('REPORTS-DEBTS-DEBT-REPORT-EXPORT-EXCEL', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }
    }

    public function exportPdf()
    {
        $this->validate();
        try {
            $parameters = $this->getParameters();
            $records = $this->getRecords($parameters);
            if ($records->count() < 1) {
                $this->customAlert('error', 'No Records Found in the selected criteria');
                return;
            }
            $this->customAlert('success', 'Exporting Pdf File');
            return redirect()->route('reports.debts.download.pdf', encrypt(json_encode($parameters)));
        } catch (\Exception $exception) {
            Log::error('REPORTS-DEBTS-DEBT-REPORT-EXPORT-PDF', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }
    }

    public function preview()
    {
        $this->validate();

        try {
            $parameters = $this->getParameters();
            $records = $this->getRecords($parameters)->get();
            if ($records->count() < 1) {
                $this->customAlert('error', 'No Records Found in the selected criteria');
                return;
            }
            return redirect()->route('reports.debts.preview', encrypt(json_encode($this->getParameters())));
        } catch (\Exception $exception) {
            Log::error('REPORTS-DEBTS-DEBT-REPORT-PREVIEW', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }
    }

    public function getParameters()
    {
        return [
            'report_type' => $this->report_type,
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
                $this->startMonth = ReportStatus::ONE;
                $this->endMonth = ReportStatus::THREE;
            } elseif ($this->quater == ReportStatus::SECOND_QUARTER) {
                $this->startMonth = ReportStatus::FOUR;
                $this->endMonth = ReportStatus::SIX;
            } elseif ($this->quater == ReportStatus::THIRD_QUARTER) {
                $this->startMonth = ReportStatus::SEVEN;
                $this->endMonth = ReportStatus::NINE;
            } elseif ($this->quater == ReportStatus::FOURTH_QUARTER) {
                $this->startMonth = ReportStatus::TEN;
                $this->endMonth = ReportStatus::TWELVE;
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
                $this->startMonth = ReportStatus::ONE;
                $this->endMonth = ReportStatus::SIX;
            } elseif ($this->semiAnnual == ReportStatus::SECOND_SEMI_ANNUAL) {
                $this->startMonth = ReportStatus::SEVEN;
                $this->endMonth = ReportStatus::TWELVE;
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
        return view('livewire.reports.debts.debt-report');
    }
}
