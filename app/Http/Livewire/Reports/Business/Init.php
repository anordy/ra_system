<?php

namespace App\Http\Livewire\Reports\Business;

use App\Enum\BusinessReportType;
use App\Enum\GeneralConstant;
use App\Enum\ReportStatus;
use App\Exports\BusinessReportExport;
use App\Exports\TaxtypeReportExport;
use App\Exports\TaxpayerReportExport;
use App\Models\BusinessActivity;
use App\Models\BusinessCategory;
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
use App\Traits\CustomAlert;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Init extends Component
{
    use RegistrationReportTrait, CustomAlert;

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
    public $tax_type_name;

    //extra filters
    public $optionTaxRegions = [];
    public $optionBusinessCategories = [];
    public $optionBusinessActivities = [];
    public $optionBusinessConsultants = [];
    public $regions;
    public $districts;
    public $wards;

    //main inputs
    public $reportType = ReportStatus::all;
    public $isic1Id;
    public $isic2Id;
    public $isic3Id;
    public $isic4Id;
    public $tax_type_id;
    public $tax_region_id;
    public $year;
    public $month;

    //extra inputs
    public $selectedTaxReginIds;
    public $selectedBusinessCategoryIds;
    public $selectedBusinessActivityIds;
    public $selectedBusinessConsultants;
    public $region;
    public $district;
    public $ward;

    //toggle button
    public $showMoreFilters;
    public $hasData = false;

    //parameters
    public $parameters = [];

    public function rules()
    {
        return [
            'reportType' => 'required|string',
            'isic1Id' => $this->reportType == BusinessReportType::NATURE ? 'required|numeric' : '',
            'tax_type_id' => $this->reportType == BusinessReportType::TAX_TYPE ? 'required' : '', // Not adding numeric validation because tax types can be selected as all.
        ];
    }

    public function mount()
    {
        //main filters
        $this->optionReportTypes = [
            BusinessReportType::NATURE => 'Registered Business By Nature of Business',
            BusinessReportType::TAX_TYPE => 'Registered Business By Tax Type',
            BusinessReportType::TAXPAYER => 'Registered Business By Tax Payer',
            BusinessReportType::WO_ZNO => 'Registered Business With No ZITAS Number',
        ];

        $this->optionIsic1s = ISIC1::query()->select('id', 'description')->get();
        $this->optionTaxTypes = TaxType::query()->select('id', 'name')->where('category', 'main')->get();
        $this->optionTurnOverTypes = [
            ReportStatus::LAST_12_MONTHS => 'Turn Over for Last 12 Months',
            ReportStatus::NEXT_12_MONTHS => 'Turn Over for Next 12 Months',
        ];

        $this->optionYears = FinancialYear::orderBy('code', 'desc')->pluck('code')->toArray();
        $this->optionMonths = ReportStatus::MONTHS_DESC;
        $this->year = ReportStatus::all;
        $this->month = ReportStatus::all;

        //extra filters
        $this->optionTaxRegions = TaxRegion::pluck('name', 'id')->toArray();
        $this->selectedTaxReginIds = $this->optionTaxRegions;

        $this->optionBusinessCategories = BusinessCategory::pluck('name', 'id')->toArray();
        $this->selectedBusinessCategoryIds = $this->optionBusinessCategories;

        $this->optionBusinessActivities = BusinessActivity::pluck('name', 'id')->toArray();
        $this->selectedBusinessActivityIds = $this->optionBusinessActivities;

        $this->optionBusinessConsultants = [
            ReportStatus::own => 'Own Consultant',
            ReportStatus::other => 'Other Cosultant',
        ];
        $this->selectedBusinessConsultants = $this->optionBusinessConsultants;

        $this->regions = Region::query()->select('id', 'name')->get();
        $this->districts = [];
        $this->wards = [];

        $this->region = ReportStatus::all;
        $this->district = ReportStatus::all;
        $this->ward = ReportStatus::all;

        //toggle filter
        $this->showMoreFilters = false;

        //initialize data
        $this->selectReportType();

        $this->extraFilters();
        $records = $this->getBusinessBuilder($this->parameters);
        if ($records->get()->count() < 1) {
            $this->hasData = false;
            $this->customAlert(GeneralConstant::ERROR, 'No Data Found for selected options');
            return;
        } else {
            $this->hasData = true;
        }
    }

    public function updated($propertyName)
    {
        if ($propertyName == 'reportType') {
            $this->reset('tax_type_id', 'isic1Id', 'isic2Id', 'isic3Id', 'isic4Id', 'optionIsic2s', 'optionIsic3s', 'optionIsic4s');
        }
        if ($propertyName == 'isic1Id') {
            $this->reset('isic2Id', 'isic3Id', 'isic4Id', 'optionIsic2s', 'optionIsic3s', 'optionIsic4s');
            if ($this->isic1Id !== null) {
                $this->optionIsic2s = ISIC2::whereIn('isic1_id', $this->isic1Id)->get();
            }
        }
        if ($propertyName == 'isic2Id') {
            $this->reset('isic3Id', 'isic4Id', 'optionIsic3s', 'optionIsic4s');
            if ($this->isic2Id !== null) {
                $this->optionIsic3s = ISIC3::whereIn('isic2_id', $this->isic2Id)->get();
            }
        }
        if ($propertyName == 'isic3Id') {
            $this->reset('isic4Id', 'optionIsic4s');
            if ($this->isic3Id !== null) {
                $this->optionIsic4s = ISIC4::whereIn('isic3_id', $this->isic3Id)->get();
            }
        }

        //Physical Location
        if ($propertyName === 'region') {
            $this->wards = [];
            $this->districts = [];
            if ($this->region != ReportStatus::all) {
                $this->districts = District::where('region_id', $this->region)->select('id', 'name')->get();
            }
            $this->ward = ReportStatus::all;
            $this->district = ReportStatus::all;
        }
        if ($propertyName === 'district') {
            $this->wards = [];
            if ($this->district != ReportStatus::all) {
                $this->wards = Ward::where('district_id', $this->district)->select('id', 'name')->get();
            }
            $this->ward = ReportStatus::all;
        }
    }

    //preview function
    public function preview()
    {
        $this->validate();
        if (!$this->checkCheckboxes()) {
            return;
        };
        $this->parameters = [];
        $this->selectReportType();
        $this->extraFilters();
        $records = $this->getBusinessBuilder($this->parameters);
        if ($records->get()->count() < 1) {
            $this->hasData = false;
            $this->customAlert(GeneralConstant::ERROR, 'No Data Found for selected options');
            return;
        } else {
            $this->hasData = true;
        }

        return redirect()->route('reports.business.preview', ['parameters' => encrypt(json_encode($this->parameters))]);
    }

    //export excel
    public function exportExcel()
    {
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

    public function selectReportType()
    {
        if ($this->reportType == ReportStatus::all) {
            $this->parameters['criteria'] = ReportStatus::ALL_BUSINESS;
        } elseif ($this->reportType == BusinessReportType::NATURE) {
            $this->parameters['criteria'] = BusinessReportType::NATURE;
            if ($this->isic4Id) {
                $this->parameters['isic_level'] = ReportStatus::ISIIC4;
                $this->parameters['isic_id'] = $this->isic4Id;
            } elseif ($this->isic3Id) {
                $this->parameters['isic_level'] = ReportStatus::ISIIC3;
                $this->parameters['isic_id'] = $this->isic3Id;
            } elseif ($this->isic2Id) {
                $this->parameters['isic_level'] = ReportStatus::ISIIC2;
                $this->parameters['isic_id'] = $this->isic2Id;
            } elseif ($this->isic1Id) {
                $this->parameters['isic_level'] = ReportStatus::ISIIC1;
                $this->parameters['isic_id'] = $this->isic1Id;
            }
        } elseif ($this->reportType == BusinessReportType::TAX_TYPE) {
            $this->parameters['criteria'] = BusinessReportType::TAX_TYPE;
            $this->parameters['taxtype_id'] = $this->tax_type_id;

            if ($this->tax_type_id != 'all') {
                $this->tax_type_name = TaxType::query()->select('name')->where('id', $this->tax_type_id)->first()->name;
                $this->parameters['tax_type_name'] = $this->tax_type_name;
            }

        } elseif ($this->reportType == BusinessReportType::TAXPAYER) {
            $this->parameters['criteria'] = BusinessReportType::TAXPAYER;
        } elseif ($this->reportType == BusinessReportType::WO_ZNO) {
            $this->parameters['criteria'] = BusinessReportType::WO_ZNO;
        }

        $this->parameters['year'] = $this->year;
        $this->parameters['range_start'] = date('Y-m-d 00:00:00', strtotime($this->range_start));
        $this->parameters['range_end'] = date('Y-m-d 23:59:59', strtotime($this->range_end));
        $this->parameters['month'] = $this->month;
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
        return view('livewire.reports.registration.registration-report');
    }

}
