<?php

namespace App\Http\Livewire\Reports\Assessment;

use App\Exports\AssessmentReportExport;
use App\Models\FinancialYear;
use App\Models\TaxType;
use App\Traits\AssessmentReportTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class AssessmentReport extends Component
{
    use LivewireAlert, AssessmentReportTrait;

    public $optionYears;
    public $optionPeriods;
    public $optionSemiAnnuals;
    public $optionQuarters;
    public $optionMonths;
    public $optionTaxTypes;

    public $showPreviewTable = false;
    public $activateButtons = false;

    public $year;
    public $month;
    public $period;
    public $quater;
    public $semiAnnual;
    public $tax_type_id = 'all';
    public $type;
    public $filing_report_type;
    public $payment_report_type;

    protected function rules()
    {
        return [
            'tax_type_id' => 'required',
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
        $this->optionTaxTypes = TaxType::whereIn('code', ['verification', 'investigation', 'audit'])->get();
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

        if ($parameters['tax_type_id'] == 'all') {
            $tax_type = 'All';
        } else {
            $tax_type = TaxType::find($parameters['tax_type_id']);
        }

        if ($parameters['year'] == 'all') {
            $fileName = $tax_type->name . '_' . 'Assessments' . '.xlsx';
            $title = 'Notice of Assessments' . ' For ' . $tax_type->name;
        } else {
            $fileName = $tax_type->name . '_' . 'Assessments' . ' - ' . $parameters['year'] . '.xlsx';
            $title = 'Assessments' . ' For ' . $tax_type->name . '-' . $parameters['year'];
        }
        $this->alert('success', 'Exporting Excel File');
        return Excel::download(new AssessmentReportExport($records, $title, $parameters), $fileName);
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
        return redirect()->route('reports.assessments.download.pdf', encrypt(json_encode($parameters)));
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
        return redirect()->route('reports.assessments.preview', encrypt(json_encode($this->getParameters())));
    }

    public function getParameters()
    {
        return [
            'tax_type_id' => $this->tax_type_id,
            'type' => $this->type,
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
        return view('livewire.reports.assessment.assessment-report');
    }
}
