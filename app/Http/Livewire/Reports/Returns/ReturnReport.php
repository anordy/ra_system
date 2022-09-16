<?php

namespace App\Http\Livewire\Reports\Returns;

use App\Exports\ReturnReportExport;
use App\Models\District;
use App\Models\FinancialYear;
use App\Models\Region;
use App\Models\TaxRegion;
use App\Models\TaxType;
use App\Models\Ward;
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

    public $year = 'all';
    public $month;
    public $period;
    public $quater;
    public $semiAnnual;
    public $tax_type_id = 'all';
    public $type;
    public $filing_report_type = 'All-Filings';
    public $payment_report_type;
    public $range_start;
    public $range_end;

    //extra filters
    public $optionTaxRegions = [];
    public $regions;
    public $districts;
    public $wards;

    public $region;
    public $district;
    public $ward;

    

    public $returnName;
    public $parameters;

    protected function rules()
    {
        return [
            'tax_type_id' => 'required',
            'type' => 'required',
            'year' => 'required',
            'period' => 'required',
            'range_start' => $this->year == 'range' ? 'required' : '',
            'range_end' => $this->year == 'range' ? 'required' : '',
            'filing_report_type' => $this->type == 'Filing' ? 'required' : '',
            'payment_report_type' => $this->type == 'Payment' ? 'required' : '',
            'period' => $this->year != 'all' && $this->year != 'range' ? 'required' : '',
            'month' => $this->period == 'Monthly' ? 'required' : '',
            'quater' => $this->period == 'Quarterly' ? 'required' : '',
            'semiAnnual' => $this->period == 'Semi-Annual' ? 'required' : '',
        ];
    }

    public function mount()
    {
        $this->optionYears = FinancialYear::orderBy('code', 'DESC')->pluck('code');
        $this->optionPeriods = ["Monthly", "Quarterly", "Semi-Annual", "Annual"];
        $this->optionSemiAnnuals = ["1st-Semi-Annual", "2nd-Semi-Annual"];
        $this->optionQuarters = ["1st-Quarter", "2nd-Quarter", "3rd-Quarter", "4th-Quarter"];
        $this->optionMonths = [1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June", 7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December"];
        $this->optionTaxTypes = TaxType::where('category', 'main')->get();
        $this->optionReportTypes = ['Filing', 'Payment'];
        $this->optionFilingTypes = ['All-Filings', 'On-Time-Filings', 'Late-Filings', 'Tax-Claims', 'Nill-Returns'];
        $this->optionPaymentTypes = ['All-Paid-Returns', 'On-Time-Paid-Returns', 'Late-Paid-Returns', 'Unpaid-Returns'];

        //extra filters
        $this->optionTaxRegions = TaxRegion::pluck('name', 'id')->toArray();
        $this->selectedTaxReginIds = $this->optionTaxRegions;
        $this->regions = Region::select('id', 'name')->get();
        $this->districts = [];
        $this->wards = [];

        $this->region = 'all';
        $this->district = 'all';
        $this->ward = 'all';

        //toggle filter
        $this->showMoreFilters = false;
    }

    public function updated($propertyName)
    {
        if ($propertyName == 'period') {
            $this->reset('month', 'quater', 'semiAnnual');
        }
        if ($propertyName == 'year') {
            $this->reset('month', 'quater', 'semiAnnual', 'period');
        }

        if ($propertyName == 'payment_report_type') {
            $this->reset('month', 'quater', 'semiAnnual', 'period', 'year');
        }

        if ($propertyName == 'filing_report_type') {
            $this->reset('month', 'quater', 'semiAnnual', 'period', 'year');
        }

        if ($propertyName == 'type') {
            $this->reset('filing_report_type', 'payment_report_type', 'month', 'quater', 'semiAnnual', 'period', 'year');
        }

        //Physical Location
        if ($propertyName === 'region') {
            $this->wards = [];
            $this->districts = [];
            if ($this->region != 'all') {
                $this->districts = District::where('region_id', $this->region)->select('id', 'name')->get();
            }
            $this->ward = 'all';
            $this->district = 'all';
        }
        if ($propertyName === 'district') {
            $this->wards = [];
            if ($this->district != 'all') {
                $this->wards = Ward::where('district_id', $this->district)->select('id', 'name')->get();
            }
            $this->ward = 'all';
        }
    }

    public function preview()
    {
        $this->validate();
        if (!$this->checkCheckboxes()) {
            return;
        };
        $this->parameters = $this->getParameters();
        // dd($this->parameters);
        $records = $this->getRecords($this->parameters);
        if ($records->count() < 1) {
            $this->alert('error', 'No Records Found in the selected criteria');
            return;
        }
        return redirect()->route('reports.returns.preview', encrypt(json_encode($this->getParameters())));
    }

    public function exportPdf()
    {
        $this->validate();
        $this->parameters = $this->getParameters();
        $records = $this->getRecords($this->parameters);
        if ($records->count() < 1) {
            $this->alert('error', 'No Records Found in the selected criteria');
            return;
        }
        $this->alert('success', 'Exporting Pdf File');
        return redirect()->route('reports.returns.download.pdf', encrypt(json_encode($this->parameters)));
    }

    public function exportExcel()
    {
        $this->validate();
        $this->parameters = $this->getParameters();
        $records = $this->getRecords($this->parameters);
        if ($records->count() < 1) {
            $this->alert('error', 'No Records Found in the selected criteria');
            return;
        }

        if ($this->parameters['year'] == 'all') {
            $fileName = $this->parameters['tax_type_name'] . '_' . $this->parameters['filing_report_type'] . '.xlsx';
            $title = $this->parameters['filing_report_type'] . ' For' . $this->parameters['tax_type_name'];
        } else {
            $fileName = $this->parameters['tax_type_name'] . '_' . $this->parameters['filing_report_type'] . ' - ' . $this->parameters['year'] . '.xlsx';
            $title = $this->parameters['filing_report_type'] . ' For' . $this->parameters['tax_type_name'] . ' ' . $this->parameters['year'];
        }
        $this->alert('success', 'Exporting Excel File');
        return Excel::download(new ReturnReportExport($records, $title, $this->parameters), $fileName);
    }


    public function getParameters()
    {
        return [
            'tax_type_id' => $this->tax_type_id ?? 'all',
            'tax_type_code' => $this->tax_type_id == 'all' ? 'all' : TaxType::find($this->tax_type_id)->code,
            'tax_type_name' => $this->tax_type_id == 'all' ? 'All Tax Types Returns' : TaxType::find($this->tax_type_id)->name,
            'type' => $this->type,
            'year' => $this->year,
            'period' => $this->period,
            'filing_report_type' => $this->filing_report_type,
            'payment_report_type' => $this->payment_report_type,
            'month' => $this->month,
            'quater' => $this->quater,
            'semiAnnual' => $this->semiAnnual,
            'dates' => $this->getStartEndDate(),
            'tax_regions' => array_keys($this->removeItemsOnFalse($this->selectedTaxReginIds)),
            'region' => $this->region,
            'district' => $this->district,
            'ward' => $this->ward,
        ];
    }


    public function getStartEndDate()
    {
        if ($this->year == "all") {
            return [
                'startDate' => null,
                'endDate' => null,
            ];
        } elseif ($this->year == "range") {
            return [
                'startDate' => date('Y-m-d', strtotime($this->range_start)),
                'endDate' => date('Y-m-d', strtotime($this->range_end)),
                'from' => date('Y-m-d 00:00:00', strtotime($this->range_start)),
                'end' => date('Y-m-d 23:59:59', strtotime($this->range_end)),
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

    public function checkCheckboxes()
    {
        //tax regions
        $taxRegionSeletected = false;
        foreach ($this->selectedTaxReginIds as $id => $value) {
            if ($value == false) {
                continue;
            } else {
                $taxRegionSeletected = true;
            }
        }
        if (!$taxRegionSeletected) {
            $this->alert('error', 'Select Atleast one Tax Region');
            return false;
        }

        return true;
    }
    public function removeItemsOnFalse($items)
    {
        foreach ($items as $key => $item) {
            if ($item == false) {
                unset($items[$key]);
            }
        }
        return $items;
    }
    public function toggleFilters()
    {
        $this->showMoreFilters = !$this->showMoreFilters;
    }

    public function render()
    {
        return view('livewire.reports.returns.return-report');
    }
}
