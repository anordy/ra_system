<?php

namespace App\Http\Livewire\Reports\Returns;

use App\Exports\ReturnReportExport;
use App\Models\FinancialYear;
use App\Models\Returns\BFO\BfoReturn;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\TaxType;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

use App\Traits\ReturnReportTrait;
use Maatwebsite\Excel\Facades\Excel;

class ReturnReport extends Component
{

    use LivewireAlert, ReturnReportTrait;

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

    public $returnName;
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
        $this->optionReportTypes = ['Filing', 'Payment'];
        $this->optionFilingTypes = ['On-Time-Filings','Late-Filings', 'All-Filings'];
        $this->optionPaymentTypes = ['All-Paid-Returns','On-Time-Paid-Returns','Late-Paid-Returns', 'Unpaid-Returns'];
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

    public function exportExcel()
    {
        $this->validate();
        $parameters = $this->getParameters();
        $modelData = $this->getModelData($parameters);
        $records = $this->getRecords($modelData['model'], $parameters);
        if($records->count()<1){
            $this->alert('error', 'No Records Found in the selected criteria');
            return;
        }
        $for = $parameters['type'] == 'Filing' ? $parameters['filing_report_type'] : $parameters['payment_report_type'];
        $for = str_replace('-', ' ', $for);
        // dd($parameters);
        if($parameters['year']=='all'){
            $fileName = 'Return Records ('.$for.').xlsx';
            $title = $modelData['returnName'].' Return Records ('.$for.')';
        }else{
            $fileName = 'Return Records ('.$for.') - '.$parameters['year']. '.xlsx';
            $title = $modelData['returnName'].' Return Records ('.$for.')';
        }  
        return Excel::download(new ReturnReportExport($records,$title,$parameters), $fileName);
    }

    public function exportPdf()
    {
        $this->validate();
        $parameters = $this->getParameters();
        $modelData = $this->getModelData($parameters);
        $records = $this->getRecords($modelData['model'], $parameters);
        if($records->count()<1){
            $this->alert('error', 'No Records Found in the selected criteria');
            return;
        }
        return redirect()->route('reports.returns.download.pdf', encrypt(json_encode($parameters)));
    }

    public function preview()
    {
        $this->validate();
        $parameters = $this->getParameters();
        $modelData = $this->getModelData($parameters);
        $records = $this->getRecords($modelData['model'], $parameters);
        if($records->count()<1){
            $this->alert('error', 'No Records Found in the selected criteria');
            return;
        }
        return redirect()->route('reports.returns.preview',encrypt(json_encode($this->getParameters())));
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

    public function getParameters()
    {
        return [
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
    }

    public function getModel()
    {
        $parameters = $this->getParameters();
        switch ($parameters['tax_type_code']) {
            case 'excise-duty-mno':
                $modelData['returnName'] = 'Excise Duty MNO';
                return MnoReturn::query();
                break;
            case 'excise-duty-bfo':
                $modelData['returnName'] = 'Excise Duty BFO';
                return BfoReturn::query();
                break;
            case 'hotel-levy':
                $taxType = TaxType::where('code',TaxType::HOTEL)->first();
                $modelData['returnName'] = 'Hotel Levy';
                return HotelReturn::query()->where('tax_type_id',$taxType->id);
                break;
            case 'restaurant-levy':
                $taxType = TaxType::where('code',TaxType::RESTAURANT)->first();
                $modelData['returnName'] = 'Restaurant Levy';
                return HotelReturn::query()->where('tax_type_id',$taxType->id);
                break;
            case 'restaurant-levy':
                $taxType = TaxType::where('code',TaxType::RESTAURANT)->first();
                $modelData['returnName'] = 'Restaurant Levy';
                return HotelReturn::query()->where('tax_type_id',$taxType->id);
                break;
            case 'tour-operator-levy':
                $taxType = TaxType::where('code',TaxType::TOUR_OPERATOR)->first();
                $modelData['returnName'] = 'Tour Operator Levy';
                return HotelReturn::query()->where('tax_type_id',$taxType->id);
                break;
            case 'petroleum-levy':
                dd('petroleum-levy');
                break;
            case 'airport-service-safety-fee':
                dd('airport-service-safety-fee');
                break;
            case 'mobile-money-transfer':
                dd('mobile-money-transfer');
                break;
            case 'electronic-money-transaction':
                dd('electronic-money-transaction');
                break;
            case 'lumpsum-payment':
                dd('lumpsum-payment');
                break;
            case 'sea-service-transport-charge':
                dd('sea-service-transport-charge');
                break;
            case 'stamp-duty':
                $modelData['returnName'] = 'Stamp Duty';
                return StampDutyReturn::query();
                break;
        }
    }
}
