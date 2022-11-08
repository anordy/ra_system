<?php

namespace App\Http\Livewire\Reports\Claims;

use App\Exports\ClaimsReportExport;
use App\Models\FinancialYear;
use App\Models\Taxpayer;
use App\Traits\ClaimReportTrait;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ClaimsReport extends Component
{
    use LivewireAlert, ClaimReportTrait;

    public $optionYears;
    public $optionPeriods;
    public $optionSemiAnnuals;
    public $optionQuarters;
    public $optionMonths;
    public $optionTaxPayers;
    public $showPreviewTable = false;
    public $activateButtons = false;


    public $year;
    public $month;
    public $period;
    public $quater;
    public $semiAnnual;
    public $taxpayer = 'all';
    public $status;
    public $payment_status;
    public $payment_method;
    public $duration;
    public $from;
    public $to;
    public $today;

    protected function rules()
    {
        return [
            'status' => 'required',
            'duration' => 'required',
            'year' => $this->duration == 'yearly' ? 'required' : '',
            'from' => $this->duration == 'date_range' ? 'required|date' : '',
            'to' => $this->duration == 'date_range' ? 'required|date|after:from' : '',
            'payment_status' => $this->status == 'approved' || $this->status == 'all' ? 'required' : '',
            'payment_method'=> $this->status == 'approved' || $this->status == 'all' ? 'required' : '',
            'period' => $this->year != 'all' && !empty($this->year) ? 'required' : '',
            'month' => $this->period == 'Monthly' ? 'required' : '',
            'quater' => $this->period == 'Quarterly' ? 'required' : '',
            'semiAnnual' => $this->period == 'Semi-Annual' ? 'required' : '',
        ];
    }

    public function mount()
    {
        $this->today = date('Y-m-d');
        $this->optionYears = FinancialYear::orderBy('code', 'DESC')->pluck('code');
        $this->optionPeriods = ["Monthly", "Quarterly", "Semi-Annual", "Annual"];
        $this->optionSemiAnnuals = ["1st-Semi-Annual", "2nd-Semi-Annual"];
        $this->optionQuarters = ["1st-Quarter", "2nd-Quarter", "3rd-Quarter", "4th-Quarter"];
        $this->optionMonths = [1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June", 7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December"];
        $this->optionTaxPayers = Taxpayer::query()->select('id', 'first_name', 'middle_name', 'last_name')->orderBy('first_name')->get();
    }

    public function updated($propertyName)
    {
        if ($propertyName == 'period') {
            $this->reset('month', 'quater', 'semiAnnual');
        }
        if ($propertyName == 'year') {
            $this->reset('month', 'quater', 'semiAnnual', 'period');
        }

        if ($this->status == 'rejected' or $this->status == 'pending') {
            $this->payment_status = '';
            $this->payment_method = '';
        }

        if ($this->duration == 'yearly') {
            $this->from = '';
            $this->to = '';
        } else {
            $this->year = '';
        }
    }

    public function preview()
    {
        if (!Gate::allows('managerial-claim-report-preview')) {
            abort(403);
        }
        $this->validate();
        $parameters = $this->getParameters();
        $records = $this->getRecords($parameters);
        if ($records->count() < 1) {
            $this->alert('error', 'No Records Found in the selected criteria');
            return;
        }
        return redirect()->route('reports.claims.preview', encrypt(json_encode($this->getParameters())));
    }

    public function exportPdf()
    {
        if (!Gate::allows('managerial-claim-report-pdf')) {
            abort(403);
        }
        $this->validate();
        $parameters = $this->getParameters();
        $records = $this->getRecords($parameters);
        if ($records->count() < 1) {
            $this->alert('error', 'No Records Found in the selected criteria');
            return;
        }
        $this->alert('success', 'Exporting Pdf File');
        return redirect()->route('reports.claim.download.pdf', encrypt(json_encode($parameters)));
    }

    public function exportExcel()
    {
        if (!Gate::allows('managerial-claim-report-excel')) {
            abort(403);
        }
        $this->validate();
        $parameters = $this->getParameters();
        $records = $this->getRecords($parameters);
        if ($records->count() < 1) {
            $this->alert('error', 'No Records Found in the selected criteria');
            return;
        }

        if ($parameters['duration'] == 'yearly') {
            if ($parameters['year'] == 'all') {
                $fileName = 'claim_report.xlsx';
                $title = 'All Claim reports';
            } else {
                if ($parameters['status'] != 'all') {
                    $fileName = $parameters['status'].'_claim_report.xlsx';
                    $title = $parameters['status'] . ' claim reports from ' . $parameters['dates']['from'] . ' to ' . $parameters['dates']['to'] . '';
                } else {
                    $fileName = 'claim_report.xlsx';
                    $title = 'All claim reports from ' . $parameters['dates']['from'] . ' to ' . $parameters['dates']['to'] . '';
                }
            }
        } else {
            if ($parameters['status'] != 'all') {
                $fileName = $parameters['status'].'_claim_report.xlsx';
                $title = $parameters['status'] . ' claim reports from ' . $parameters['from'] . ' to ' . $parameters['to'] . '';
            } else {
                $fileName = 'claim_report.xlsx';
                $title = 'All Claim reports from ' . $parameters['from'] . ' to ' . $parameters['to'] . '';
            }
        }

        $this->alert('success', 'Exporting Excel File');
        return Excel::download(new ClaimsReportExport($records, $title, $parameters), $fileName);
    }


    public function getParameters()
    {
        return [
            'tax_payer_id' => $this->taxpayer ?? 'all',
            'status' => $this->status,
            'duration' => $this->duration,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'from' => $this->from,
            'to' => $this->to,
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
        return view('livewire.reports.claims.claims-report');
    }
}
