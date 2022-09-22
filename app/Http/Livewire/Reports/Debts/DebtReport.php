<?php

namespace App\Http\Livewire\Reports\Debts;

use App\Exports\Debts\AssessmentDebtReportExport;
use App\Exports\Debts\DebtReturnReportExport;
use App\Exports\Debts\DebtWaiverReportExport;
use App\Exports\Debts\DemandNoticeReportExport;
use App\Models\FinancialYear;
use App\Traits\DebtReportTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class DebtReport extends Component
{
    use LivewireAlert, DebtReportTrait;

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
    public $report_type = 'all';
    public $filing_report_type;
    public $payment_report_type;

    protected function rules()
    {
        return [
            'report_type' => 'required',
            'year' => 'required',
            'period' => 'required',
            'period' => $this->year != 'all' ? 'required' : '',
            'month' => $this->period == 'Monthly' ? 'required' : '',
            'quater' => $this->period == 'Quarterly' ? 'required' : '',
            'semiAnnual' => $this->period == 'Semi-Annual' ? 'required' : '',
        ];
    }

    public function mount()
    {
        $this->optionYears = FinancialYear::pluck('code');
        $this->optionReportTypes = ["Returns", "Assessments", "Waiver", "Installment", "Demand-Notice"];
        $this->optionPeriods = ["Monthly", "Quarterly", "Semi-Annual", "Annual"];
        $this->optionSemiAnnuals = ["1st-Semi-Annual", "2nd-Semi-Annual"];
        $this->optionQuarters = ["1st-Quarter", "2nd-Quarter", "3rd-Quarter", "4th-Quarter"];
        $this->optionMonths = [1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June", 7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December"];
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
        $parameters = $this->getParameters();
        $records = $this->getRecords($parameters);
        if ($records->count() < 1) {
            $this->alert('error', 'No Records Found in the selected criteria');
            return;
        }

        if ($parameters['report_type'] == 'all') {
            $report_type = 'All';
        } else {
            $report_type = $this->report_type;
        }

        if ($parameters['year'] == 'all') {
            $fileName = "{$report_type}.xlsx";
            $title = "Debt Report for {$report_type}";
        } else {
            $fileName = "{$report_type}-{$parameters['year']}.xlsx";
            $title = "Debt Report for {$report_type}-{$parameters['year']}";
        }
        $this->alert('success', 'Exporting Excel File');

        if ($parameters['report_type'] == 'Assessments') {
            return Excel::download(new AssessmentDebtReportExport($records, $title, $parameters), $fileName);
        } else if ($parameters['report_type'] == 'Returns') {
            return Excel::download(new DebtReturnReportExport($records, $title, $parameters), $fileName);
        } else if ($parameters['report_type'] == 'Waiver') {
            return Excel::download(new DebtWaiverReportExport($records, $title, $parameters), $fileName);
        }else if ($parameters['report_type'] == 'Demand Notice') {
            return Excel::download(new DemandNoticeReportExport($records, $title, $parameters), $fileName);
        }
    }

    public function exportPdf()
    {
        $this->validate();
        $parameters = $this->getParameters();
        $records = $this->getRecords($parameters);
        if ($records->count() < 1) {
            $this->alert('error', 'No Records Found in the selected criteria');
            return;
        }
        $this->alert('success', 'Exporting Pdf File');
        return redirect()->route('reports.debts.download.pdf', encrypt(json_encode($parameters)));
    }

    public function preview()
    {
        $this->validate();
        $parameters = $this->getParameters();
        $records = $this->getRecords($parameters)->get();
        if ($records->count() < 1) {
            $this->alert('error', 'No Records Found in the selected criteria');
            return;
        }
        return redirect()->route('reports.debts.preview', encrypt(json_encode($this->getParameters())));
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
        ];
    }

    public function getStartEndDate()
    {
        if ($this->year == "all") {
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
            if ($this->quater == '1st-Quarter') {
                $this->startMonth = 1;
                $this->endMonth = 3;
            } elseif ($this->quater == '2nd-Quarter') {
                $this->startMonth = 4;
                $this->endMonth = 6;
            } elseif ($this->quater == '3rd-Quarter') {
                $this->startMonth = 7;
                $this->endMonth = 9;
            } elseif ($this->quater == '4th-Quarter') {
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
            if ($this->semiAnnual == '1st-Semi-Annual') {
                $this->startMonth = 1;
                $this->endMonth = 6;
            } elseif ($this->semiAnnual == '2nd-Semi-Annual') {
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
        return view('livewire.reports.debts.debt-report');
    }
}
