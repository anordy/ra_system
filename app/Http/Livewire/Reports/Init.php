<?php

namespace App\Http\Livewire\Reports;

use App\Enum\BusinessReportType;
use App\Enum\GeneralConstant;
use App\Enum\ReportStatus;
use App\Exports\BusinessReportExport;
use App\Exports\TaxtypeReportExport;
use App\Exports\TaxpayerReportExport;
use App\Models\TaxType;
use App\Models\Ward;
use App\Traits\RegistrationReportTrait;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Init extends Component
{
    use  CustomAlert;


    public $today;
    public $range_start;
    public $range_end;

    //main filters
    public $optionReportTypes;
    public $optionYears = [];
    public $optionMonths = [];

    //main inputs
    public $reportType = ReportStatus::all;

    public $year;
    public $month;

    public $reports;
    public $report;
    //toggle button
    public $showMoreFilters;
    public $hasData = false;

    //parameters
    public $parameters = [];
    public $leakages;
    public $leakage;

    public function rules()
    {
        return [
            'reportType' => 'required|string'
        ];
    }

    public function mount()
    {
       //main filters
       $this->optionReportTypes = [
        BusinessReportType::CHANNEL => 'Channel',
        BusinessReportType::SOURCE => 'Source',
    ];

    $this->leakages = [
        BusinessReportType::REVENUE_LOSS => 'Revenue Loss',
        BusinessReportType::OVERCHARGING => 'Overcharging',
    ];
    $this->optionMonths = ReportStatus::MONTHS_DESC;
    // $this->year = ReportStatus::all;
    // $this->month = ReportStatus::all;


    $this->getReport();
    $this->getTotal();
    }

    public function updated($propertyName)
    {
       
    }

    //preview function
    public function preview()
    {
       
    }

    //export excel
    public function exportExcel()
    {
        return;
        $this->validate();
        if (!$this->checkCheckboxes()) {
            return;
        };
        $this->parameters = [];
        $this->selectReportType();
        $this->extraFilters();

        $records = $this->getBusinessBuilder($this->parameters);
        if ($records->get()->count() < 1) {
            $this->customAlert(GeneralConstant::ERROR, 'No Data Found for selected options');
            return;
        } else {
            $this->customAlert(GeneralConstant::SUCCESS, 'Exporting Excel File');
        }
        if (isset($this->parameters['taxtype_id']) == ReportStatus::all) {
            return Excel::download(new TaxtypeReportExport($this->getBusinessBuilder($this->parameters)), 'Tax-type.xlsx');
        } elseif ($this->parameters['criteria'] == BusinessReportType::TAXPAYER) {
            return Excel::download(new TaxpayerReportExport($this->getBusinessBuilder($this->parameters)), 'Taxpayer.xlsx');
        } else {
            return Excel::download(new BusinessReportExport($this->getBusinessBuilder($this->parameters)), 'Business.xlsx');
        }
    }

    public function exportPdf()
    {
        return;
        $this->validate();
        if (!$this->checkCheckboxes()) {
            return;
        };

        $this->parameters = [];
        $this->selectReportType();
        $this->extraFilters();
        $records = $this->getBusinessBuilder($this->parameters);

        if ($records->get()->count() < 1) {
            $this->customAlert(GeneralConstant::ERROR, 'No Data Found for selected options');
            return;
        } else {
            $this->customAlert(GeneralConstant::SUCCESS, 'Exporting Pdf File');
        }

        if (isset($this->parameters['taxtype_id']) == ReportStatus::all) {
            return redirect()->route('reports.taxtype.download.pdf', encrypt(json_encode($this->parameters)));
        } elseif ($this->parameters['criteria'] == BusinessReportType::TAXPAYER) {
            return redirect()->route('reports.taxpayer.download.pdf', encrypt(json_encode($this->parameters)));
        } else {
            return redirect()->route('reports.business.download.pdf', encrypt(json_encode($this->parameters)));
        }
    }

    public function checkCheckboxes()
    {
        $taxRegionSelected = false;
        foreach ($this->selectedTaxReginIds as $value) {
            if (!$value) {
                continue;
            } else {
                $taxRegionSelected = true;
            }
        }

        if (!$taxRegionSelected) {
            $this->customAlert(GeneralConstant::ERROR, 'Select at least one Tax Region');
            return false;
        }

        $businessCategoriesSeletected = false;
        foreach ($this->selectedBusinessCategoryIds as $value) {
            if ($value == false) {
                continue;
            } else {
                $businessCategoriesSeletected = true;
            }
        }

        if (!$businessCategoriesSeletected) {
            $this->customAlert(GeneralConstant::ERROR, 'Select at least one Business Category');
            return false;
        }

        $businessActivitiesSeletected = false;
        foreach ($this->selectedBusinessActivityIds as $value) {
            if ($value == false) {
                continue;
            } else {
                $businessActivitiesSeletected = true;
            }
        }

        if (!$businessActivitiesSeletected) {
            $this->customAlert(GeneralConstant::ERROR, 'Select at least one Business Activity Type');
            return false;
        }

        $businessConsultantsSeletected = false;
        foreach ($this->selectedBusinessConsultants as $value) {
            if ($value == false) {
                continue;
            } else {
                $businessConsultantsSeletected = true;
            }
        }

        if (!$businessConsultantsSeletected) {
            $this->customAlert(GeneralConstant::ERROR, 'Select at least one Business Consultant Type');
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

    protected function getReport($currency = 'USD'){
        $this->report['USD'] = $this->queryData('USD');
        $this->report['TZS'] = $this->queryData('TZS');
    }

    public function queryData($currency){

        $query = DB::table('ra_incedents as ra')
        ->select(
            'b.name as channel',
            'iss.type',
            'iss.currency',
            DB::raw('SUM(iss.detected) as detected'),
            DB::raw('SUM(iss.prevented) as prevented'),
            DB::raw('SUM(iss.recovered) as recovered')
        )
        ->leftJoin('bank_channels as b', 'b.id', '=', 'ra.bank_channel_id')
        ->leftJoin('ra_issues as iss', 'iss.ra_incident_id', '=', 'ra.id')
        ->groupBy('b.name', 'iss.type', 'iss.currency')
        ->where('iss.type', '=', 'Revenue Loss')
        // ->where('iss.currency','=','TZS')
        ->get();

        $this->reports = $query;
        return $this->reports;
    }

    public function getTotal(){
        $query = DB::table('ra_incedents as ra')
        ->select(
            'iss.currency',
            DB::raw('SUM(iss.detected) as detected'),
            DB::raw('SUM(iss.prevented) as prevented'),
            DB::raw('SUM(iss.recovered) as recovered')
        )
        ->leftJoin('bank_channels as b', 'b.id', '=', 'ra.bank_channel_id')
        ->leftJoin('ra_issues as iss', 'iss.ra_incident_id', '=', 'ra.id')
        ->groupBy('b.name', 'iss.type', 'iss.currency')
        ->where('iss.type', '=', 'Revenue Loss')
        // ->where('iss.currency','=','TZS')
        ->get();
        // dd($query);
    }

    public function extraFilters()
    {
        $this->parameters['tax_regions'] = array_keys($this->removeItemsOnFalse($this->selectedTaxReginIds));
        $this->parameters['category_ids'] = array_keys($this->removeItemsOnFalse($this->selectedBusinessCategoryIds));
        $this->parameters['activity_ids'] = array_keys($this->removeItemsOnFalse($this->selectedBusinessActivityIds));
        $this->parameters['consultants'] = $this->removeItemsOnFalse($this->selectedBusinessConsultants);
        $this->parameters['region'] = $this->region;
        $this->parameters['district'] = $this->district;
        $this->parameters['ward'] = $this->ward;
    }


    public function render()
    {
        return view('livewire.reports.init');
    }

}
