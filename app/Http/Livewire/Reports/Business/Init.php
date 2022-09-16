<?php

namespace App\Http\Livewire\Reports\Business;

use App\Exports\BusinessRegByLastTurnOverReportExport;
use App\Exports\BusinessRegByNatureReportExport;
use App\Exports\BusinessRegByNextTurnOverReportExport;
use App\Exports\BusinessRegByTaxTypeReportExport;
use App\Exports\BusinessReportExport;
use App\Models\BusinessActivity;
use App\Models\BusinessCategory;
use App\Models\Currency;
use App\Models\District;
use App\Models\FinancialYear;
use App\Models\ISIC1;
use App\Models\ISIC2;
use App\Models\ISIC3;
use App\Models\ISIC4;
use App\Models\Region;
use App\Models\TaxRegion;
use App\Models\TaxType;
use App\Models\Ward;
use App\Traits\RegistrationReportTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Init extends Component
{
    use RegistrationReportTrait, LivewireAlert;

    //main filters
    public $optionReportTypes;
    public $optionIsic1s = [];
    public $optionIsic2s = [];
    public $optionIsic3s = [];
    public $optionIsic4s = [];
    public $optionTaxTypes = [];
    public $optionTurnOverTypes = [];
    public $optionYears = [];
    public $optionMonths = [];
    public $range_start;
    public $range_end;

    //extra filters
    public $optionTaxRegions = [];
    public $optionBusinessCategories = [];
    public $optionBusinessActivities = [];
    // public $optionBusinessCurrencies = [];
    public $optionBusinessConsultants = [];
    public $regions;
    public $districts;
    public $wards;

    //main inputs
    public $reportType = 'all';
    public $isic1Id;
    public $isic2Id;
    public $isic3Id;
    public $isic4Id;
    public $tax_type_id;
    // public $turn_over_type;
    // public $turn_over_from_amount;
    // public $turn_over_to_amount;
    public $tax_region_id;
    public $year;
    public $month;

    //extra inputs
    public $selectedTaxReginIds;
    public $selectedBusinessCategoryIds;
    public $selectedBusinessActivityIds;
    // public $selectedBusinessCurrencyIds;
    public $selectedBusinessConsultants;
    public $region;
    public $district;
    public $ward;

    //toggle button
    public $showMoreFilters;

    //paramenters
    public $parameters = [];

    public function rules()
    {
        return [
            'reportType' => 'required',
            'isic1Id' => $this->reportType == 'Business-Reg-By-Nature' ? 'required' : '',
            'tax_type_id' => $this->reportType == 'Business-Reg-By-TaxType' ? 'required' : '',
            // 'turn_over_from_amount' => $this->reportType == 'Business-Reg-By-Turn-Over' ? 'required|numeric' : '',
            // 'turn_over_to_amount' => $this->reportType == 'Business-Reg-By-Turn-Over' ? 'required|numeric|gt:turn_over_from_amount' : '',
        ];
    }

    public function mount()
    {
        //main filters
        $this->optionReportTypes = [
            'Business-Reg-By-Nature' => 'Registered Business By Nature of Business',
            'Business-Reg-By-TaxType' => 'Registered Business By Tax Type',
            // 'Business-Reg-By-Turn-Over' => 'Registered Business By Turn Over',
        ];
        $this->optionIsic1s = ISIC1::all();
        $this->optionTaxTypes = TaxType::where('category', 'main')->get();
        $this->optionTurnOverTypes = [
            'Last-12-Months' => 'Turn Over for Last 12 Months',
            'Next-12-Months' => 'Turn Over for Next 12 Months',
        ];
        $this->optionYears = FinancialYear::orderBy('code', 'desc')->pluck('code')->toArray();
        $this->optionMonths = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];
        $this->year = 'all';
        $this->month = 'all';

        //extra filters
        $this->optionTaxRegions = TaxRegion::pluck('name', 'id')->toArray();
        $this->selectedTaxReginIds = $this->optionTaxRegions;

        $this->optionBusinessCategories = BusinessCategory::pluck('name', 'id')->toArray();
        $this->selectedBusinessCategoryIds = $this->optionBusinessCategories;

        $this->optionBusinessActivities = BusinessActivity::pluck('name', 'id')->toArray();
        $this->selectedBusinessActivityIds = $this->optionBusinessActivities;

        // $this->optionBusinessCurrencies = Currency::pluck('iso', 'id')->toArray();
        // $this->selectedBusinessCurrencyIds = $this->optionBusinessCurrencies;

        $this->optionBusinessConsultants = [
            'own' => 'Own Consultant',
            'other' => 'Other Cosultant',
        ];
        $this->selectedBusinessConsultants = $this->optionBusinessConsultants;

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
        if ($propertyName == 'reportType') {
            $this->reset('tax_type_id','isic1Id','isic2Id', 'isic3Id', 'isic4Id', 'optionIsic2s', 'optionIsic3s', 'optionIsic4s');
        }
        if ($propertyName == 'isic1Id') {
            $this->reset('isic2Id', 'isic3Id', 'isic4Id', 'optionIsic2s', 'optionIsic3s', 'optionIsic4s');
            if ($this->isic1Id !== null) {
                $this->optionIsic2s = ISIC2::where('isic1_id', $this->isic1Id)->get();
            }
        }
        if ($propertyName == 'isic2Id') {
            $this->reset('isic3Id', 'isic4Id', 'optionIsic3s', 'optionIsic4s');
            if ($this->isic2Id !== null) {
                $this->optionIsic3s = ISIC3::where('isic2_id', $this->isic2Id)->get();
            }
        }
        if ($propertyName == 'isic3Id') {
            $this->reset('isic4Id', 'optionIsic4s');
            if ($this->isic3Id !== null) {
                $this->optionIsic4s = ISIC4::where('isic3_id', $this->isic3Id)->get();
            }
        }
        if ($propertyName == 'year') {
            // $this->reset('month');
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

    //preview function
    public function preview()
    {
        $this->validate();
        if (!$this->checkCheckboxes()) {
            return;
        };
        $this->parameters=[];
        $this->selectReportType();
        $this->extraFilters();
        $records = $this->getBusinessBuilder($this->parameters);
        if($records->get()->count()<1){
            $this->alert('error','No Data Found for selected options');
            return;
        }
        return redirect()->route('reports.business.preview',encrypt(json_encode($this->parameters)));
    }

    //export excel
    public function exportExcel()
    {
        $this->validate();
        if (!$this->checkCheckboxes()) {
            return;
        };
        $this->parameters=[];
        $this->selectReportType();
        $this->extraFilters();

        $records = $this->getBusinessBuilder($this->parameters);
        if($records->get()->count()<1){
            $this->alert('error','No Data Found for selected options');
            return;
        }else{
            $this->alert('success','Exporting Excel File');
        }
        return Excel::download(new BusinessReportExport($this->getBusinessBuilder($this->parameters)), 'Business.xlsx');
    }

    public function exportPdf()
    {
        $this->validate();
        if (!$this->checkCheckboxes()) {
            return;
        };
        $this->parameters=[];
        $this->selectReportType();
        $this->extraFilters();

        $records = $this->getBusinessBuilder($this->parameters);
        if($records->get()->count()<1){
            $this->alert('error','No Data Found for selected options');
            return;
        }else{
            $this->alert('success','Exporting Pdf File');
        }
        return redirect()->route('reports.business.download.pdf',encrypt(json_encode($this->parameters)));
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

        //Business Categories
        $businessCategoriesSeletected = false;
        foreach ($this->selectedBusinessCategoryIds as $id => $value) {
            if ($value == false) {
                continue;
            } else {
                $businessCategoriesSeletected = true;
            }
        }
        if (!$businessCategoriesSeletected) {
            $this->alert('error', 'Select Atleast one Business Category');
            return false;
        }

        //Business Activities
        $businessActivitiesSeletected = false;
        foreach ($this->selectedBusinessActivityIds as $id => $value) {
            if ($value == false) {
                continue;
            } else {
                $businessActivitiesSeletected = true;
            }
        }
        if(!$businessActivitiesSeletected){
            $this->alert('error', 'Select Atleast one Business Activity Type');
            return false;
        }

        //Business Consultants
        $businessConsultantsSeletected = false;
        foreach ($this->selectedBusinessConsultants as $id => $value) {
            if ($value == false) {
                continue;
            } else {
                $businessConsultantsSeletected = true;
            }
        }
        if (!$businessConsultantsSeletected) {
            $this->alert('error', 'Select Atleast one Business Consultant Type');
            return false;
        }

        return true;
    }

    public function removeItemsOnFalse($items)
    {
        foreach ($items as $key => $item) {
            if($item==false){
                unset($items[$key]);
            }
        }
        return $items;
    }

    public function toggleFilters()
    {
        $this->showMoreFilters = !$this->showMoreFilters;
    }

    public function selectReportType()
    {
        if($this->reportType == 'all'){
            $this->parameters['criteria'] = 'All-Business';
        }elseif ($this->reportType == 'Business-Reg-By-Nature') {
                $this->parameters['criteria'] = 'Business-Reg-By-Nature';
            if ($this->isic4Id) {
                $this->parameters['isic_level'] = 4;
                $this->parameters['isic_id'] = $this->isic4Id;
            } elseif ($this->isic3Id) {
                $this->parameters['isic_level'] = 3;
                $this->parameters['isic_id'] = $this->isic3Id;
            } elseif ($this->isic2Id) {
                $this->parameters['isic_level'] = 2;
                $this->parameters['isic_id'] = $this->isic2Id;
            } elseif ($this->isic1Id) {
                $this->parameters['isic_level'] = 1;
                $this->parameters['isic_id'] = $this->isic1Id;
            }
        } elseif ($this->reportType == 'Business-Reg-By-TaxType') {
            $this->parameters['criteria'] = 'Business-Reg-By-TaxType';
            $this->parameters['taxtype_id'] = $this->tax_type_id;
        }
        $this->parameters['year'] = $this->year;
        $this->parameters['range_start'] = date('Y-m-d 00:00:00', strtotime($this->range_start));
        $this->parameters['range_end'] = date('Y-m-d 23:59:59', strtotime($this->range_end));
        $this->parameters['month'] = $this->month;
    }

    
    public function extraFilters()
    {
        $this->parameters['tax_regions']=array_keys($this->removeItemsOnFalse($this->selectedTaxReginIds));
        $this->parameters['category_ids']=array_keys($this->removeItemsOnFalse($this->selectedBusinessCategoryIds));
        $this->parameters['activity_ids']=array_keys($this->removeItemsOnFalse($this->selectedBusinessActivityIds));
        // $this->parameters['currency_ids']=array_keys($this->selectedBusinessCurrencyIds);
        $this->parameters['consultants']=$this->removeItemsOnFalse($this->selectedBusinessConsultants);
        $this->parameters['region']=$this->region;
        $this->parameters['district']=$this->district;
        $this->parameters['ward']=$this->ward;
    }
    

    public function render()
    {
        return view('livewire.reports.registration.registration-report');
    }

}
