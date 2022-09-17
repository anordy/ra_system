<?php

namespace App\Http\Livewire\LandLease;

use App\Models\FinancialYear;
use Livewire\Component;
use App\Exports\LeasePaymentExport;
use Maatwebsite\Excel\Facades\Excel;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\LandLease;
use App\Models\LeasePayment;
use App\Traits\LeasePaymentReportTrait;
use Illuminate\Support\Facades\Gate;

class PaymentReport extends Component
{

    use LivewireAlert, LeasePaymentReportTrait;
    //values for selects
    public $year;
    public $period;
    public $month;
    public $quater;
    public $semiAnnual;
    public $status;
    public $date_type = "created_at";

    //select options
    public $optionYears;
    public $optionPeriods;
    public $optionMonths;
    public $optionQuarters;
    public $optionSemiAnnuals;

    //hide/show elements
    public $showOptions;
    public $showMonths;
    public $showQuarters;
    public $showSemiAnnuals;

    //backend variables
    public $startMonth;
    public $endMonth;
    public $range_start;
    public $range_end;


    public function mount()
    {
        //set current year at first
        $this->year = date('Y');
        $this->period = 'Monthly';
        $this->month = strval(intval(date('m')));
        $this->quater = "1st-Quarter";
        $this->semiAnnual = "1st-Semi-Annual";

        //get options for years
        $optionStartYear = intval(FinancialYear::first()->code);
        $this->optionYears = range($optionStartYear, date('Y'));

        //add All to year options
        $this->optionYears[] = "Custom Range";
        $this->optionYears[] = "All";
        //sort array
        rsort($this->optionYears);

        //set values
        $this->optionPeriods = ["Monthly", "Quarterly", "Semi-Annual", "Annual"];
        $this->optionMonths = array(1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June", 7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December");
        $this->optionQuarters = array("1st-Quarter", "2nd-Quarter", "3rd-Quarter", "4th-Quarter");
        $this->optionSemiAnnuals = array("1st-Semi-Annual", "2nd-Semi-Annual");
        $this->showOptions = true;
        $this->showMonths = true;
        $this->showQuarters = false;
        $this->showSemiAnnuals = false;

        $this->emitTo('land-lease.lease-payment-report-table', 'refreshTable',  $this->getParameters());
    }

    public function preview(){
        // dd($this->getStartEndDate());
      $this->emitTo('land-lease.lease-payment-report-table', 'refreshTable',  $this->getParameters());
    }

    public function export()
    {
        if(!Gate::allows('land-lease-generate-report')){
            abort(403);
        }

        $dates = $this->getStartEndDate();

        if($dates['startDate'] == null || $dates['endDate'] == null) {
            $exists = LeasePayment::exists();
            if ($this->status) {
                $exists = LeasePayment::where('lease_payments.status', $this->status)->exists();
            }
            if($exists){
                $this->alert('success', 'Downloading file');
                // dd($this->date_type);
                return Excel::download(new LeasePaymentExport($dates['startDate'], $dates['endDate'], $this->status, $this->date_type), 'Land Leases Payment All Records.xlsx');
            }else{
                $this->alert('error', "No data found.");
            } 
        }


        if ($this->date_type == 'payment_month') {
            $months = $this->getMonthList($dates);
                $years = $this->getYearList($dates);
                $model = LeasePayment::query()
                ->leftJoin('land_leases', 'land_leases.id', 'lease_payments.land_lease_id')
                ->leftJoin('financial_years', 'financial_years.id', 'lease_payments.financial_year_id')
                ->whereIn("land_leases.{$this->date_type}", $months)
                ->whereIn("financial_years.code", $years);

        } elseif ($this->date_type == 'payment_year') {
            $years = $this->getYearList($dates);
            $leasePayment = LeasePayment::query()
            ->leftJoin('financial_years', 'financial_years.id', 'lease_payments.financial_year_id')
            ->whereIn("financial_years.code", $years);
        } else{

            $leasePayment =LeasePayment::whereBetween("{$this->date_type}", [$dates['startDate'], $dates['endDate']]);   
        }

        
        if ($this->status) {
            $leasePayment = clone $leasePayment->where('lease_payments.status', $this->status);
        }

        $exists = $leasePayment->exists();

        if ($exists) {
            $this->alert('success', 'Downloading file');
            return Excel::download(new LeasePaymentExport($dates['startDate'], $dates['endDate'], $this->status, $this->date_type), 'Land Leases Payment FROM ' . $dates['from'] . ' TO ' . $dates['to'] . '.xlsx');
        } else {
            $this->alert('error', "No data found for the selected period.");
        }
    }

    public function exportPdf()
    {
        if(!Gate::allows('land-lease-generate-report')){
            abort(403);
        }

        $dates = $this->getStartEndDate();
        if($dates['startDate'] == null || $dates['endDate'] == null) {
            
            $exists = LeasePayment::exists();
            if ($this->status) {
                $exists = LeasePayment::where('lease_payments.status', $this->status)->exists();
            }
            
            if($exists){
                $this->alert('success', 'Exporting Pdf File');
                return redirect()->route('land-lease.payment.download.report.pdf', encrypt(json_encode($this->getParameters())));
            }else{
                $this->alert('error', "No data found.");
            } 
        }

        if ($this->date_type == 'payment_month') {
            $months = $this->getMonthList($dates);
                $years = $this->getYearList($dates);
                $model = LeasePayment::query()
                ->leftJoin('land_leases', 'land_leases.id', 'lease_payments.land_lease_id')
                ->leftJoin('financial_years', 'financial_years.id', 'lease_payments.financial_year_id')
                ->whereIn("land_leases.{$this->date_type}", $months)
                ->whereIn("financial_years.code", $years);

        } elseif ($this->date_type == 'payment_year') {
            $years = $this->getYearList($dates);
            $leasePayment = LeasePayment::query()
            ->leftJoin('financial_years', 'financial_years.id', 'lease_payments.financial_year_id')
            ->whereIn("financial_years.code", $years);
        } else{

            $leasePayment =LeasePayment::whereBetween("{$this->date_type}", [$dates['startDate'], $dates['endDate']]);   
        }
        
        if ($this->status) {
            $leasePayment = clone $leasePayment->where('lease_payments.status', $this->status);
        }

        $exists = $leasePayment->exists();
        
        if ($exists) {
            $this->alert('success', 'Exporting Pdf File');
            return redirect()->route('land-lease.payment.download.report.pdf', encrypt(json_encode($this->getParameters())));
        } else {
            $this->alert('error', "No data found for the selected period.");
        }
    }

    public function updated()
    {
        if ($this->year == "All") {
            $this->showOptions = false;
        }elseif ($this->year == "Custom Range") {
                $this->showOptions = false;
        } else {
            $this->showOptions = true;

            if ($this->period == "Monthly") {
                $this->showMonths = true;
                $this->showQuarters = false;
                $this->showSemiAnnuals = false;
            } elseif ($this->period == "Quarterly") {
                $this->showMonths = false;
                $this->showQuarters = true;
                $this->showSemiAnnuals = false;
            } elseif ($this->period == "Semi-Annual") {
                $this->showMonths = false;
                $this->showQuarters = false;
                $this->showSemiAnnuals = true;
            } elseif ($this->period == "Annual") {
                $this->showMonths = false;
                $this->showQuarters = false;
                $this->showSemiAnnuals = false;
            }
        }

       $this->selectedDates = $this->getStartEndDate();

    }

    public function getParameters()
    {
        return [
            'date_type' => $this->date_type,
            'status' => $this->status,
            'dates' => $this->getStartEndDate(),
        ];
    }

    public function getStartEndDate()
    {
        if ($this->year == "All") {
            return [
                'startDate' => null,
                'endDate' => null,
            ];
        } elseif ($this->year == "Custom Range") {
            return [
                'startDate' => date('Y-m-d', strtotime($this->range_start)),
                'endDate' => date('Y-m-d', strtotime($this->range_end)),
                'from' => date('Y-m-d 00:00:00', strtotime($this->range_start)),
                'end' => date('Y-m-d 23:59:59', strtotime($this->range_end)),
            ];

        } elseif ($this->showMonths) {
            $date = \Carbon\Carbon::parse($this->year . "-" . $this->month . "-01");
            $start = $date->startOfMonth()->format('Y-m-d H:i:s');
            $end = $date->endOfMonth()->format('Y-m-d H:i:s');
            $from = $date->startOfMonth()->format('Y-m-d');
            $to = $date->endOfMonth()->format('Y-m-d');
            return ['startDate' => $start, 'endDate' => $end, 'from' => $from, 'to' => $to];
        } elseif ($this->showQuarters) {
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
        } elseif ($this->showSemiAnnuals) {
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
        return view('livewire.land-lease.payment-report');
    }
}
