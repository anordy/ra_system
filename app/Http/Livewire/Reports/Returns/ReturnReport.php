<?php

namespace App\Http\Livewire\Reports\Returns;

use App\Models\FinancialYear;
use App\Models\TaxType;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ReturnReport extends Component
{

    use LivewireAlert;

    public $optionYears;
    public $optionPeriods;
    public $optionSemiAnnuals;
    public $optionQuarters;
    public $optionMonths;
    public $optionTaxTypes;
    public $optionReportTypes;
    public $optionFilingTypes;
    public $optionPaymentReportTypes;
    public $showPreviewTable = false;
    public $activateButtons = false;

    public $year;
    public $month;
    public $period;
    public $quater;
    public $semiAnnual;
    public $tax_type_id;
    public $type;
    public $filing_report_type;
    public $payment_report_type;

    protected function rules()
    {
        return [
            'tax_type_id' => 'required',
            'type' => 'required',
            'year' => 'required',
            'period' => 'required',
            'filing_report_type' => $this->type == 'filing' ? 'required' : '',
            'payment_report_type' => $this->type == 'payment' ? 'required' : '',
            'period' => $this->year != 'all' ? 'required' : '',
            'month' => $this->period == 'Monthly' ? 'required' : '',
            'quater' => $this->period == 'Quarterly' ? 'required' : '',
            'semiAnnual' => $this->period == 'Semi-Annual' ? 'required' : '',
        ];
    }

    public function mount()
    {
        $this->optionYears = FinancialYear::pluck('code');
        $this->optionPeriods = ["Monthly", "Quarterly", "Semi-Annual", "Annual"];
        $this->optionSemiAnnuals = ["1st-Semi-Annual", "2nd-Semi-Annual"];
        $this->optionQuarters = ["1st-Quarter", "2nd-Quarter", "3rd-Quarter", "4th-Quarter"];
        $this->optionMonths = [1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June", 7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December"];
        $this->optionTaxTypes = TaxType::all();
        $this->optionReportTypes = ['filing', 'payment'];
        $this->optionFilingTypes = ['Late Filings', 'Submitted Filings'];
        $this->optionPaymentTypes = ['Late Payment', 'Paid Returns', 'Unpaid Returns'];
    }

    public function updated($propertyName)
    {
        if($propertyName=='period'){
            $this->reset('month','quater','semiAnnual');
        }
        if($propertyName == 'year'){
            $this->reset('month','quater','semiAnnual','period');
        }

        if($propertyName == 'type'){
            $this->reset('filing_report_type','payment_report_type');
        }
    }

    public function preview()
    {
        $this->validate();

        $parameters = [
            'tax_type_id' => $this->tax_type_id,
            'tax_type_code' => TaxType::find($this->tax_type_id)->code,
            'tax_type_name' => TaxType::find($this->tax_type_id)->name,
            'type' => $this->type,
            'year' => $this->year,
            'period' => $this->period,
            'filing_report_type' => $this->filing_report_type,
            'payment_report_type' => $this->payment_report_type,
            'month' => $this->month,
            'quater' => $this->quater,
            'semiAnnual' => $this->semiAnnual,
            'dates'=> $this->getStartEndDate(),
        ];

        return redirect()->route('reports.returns.preview',encrypt(json_encode($parameters)));

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
        return view('livewire.reports.returns.return-report');
    }
}
