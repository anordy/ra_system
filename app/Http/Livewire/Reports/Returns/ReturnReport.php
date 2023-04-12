<?php

namespace App\Http\Livewire\Reports\Returns;

use App\Exports\ReturnReportExport;
use App\Models\District;
use App\Models\FinancialYear;
use App\Models\Region;
use App\Models\Returns\Vat\SubVat;
use App\Models\TaxRegion;
use App\Models\TaxType;
use App\Models\Ward;
use Livewire\Component;
use App\Traits\CustomAlert;

use App\Traits\ReturnReportTrait;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ReturnReport extends Component
{

    use CustomAlert, ReturnReportTrait;

    public $optionTaxTypes;
    public $optionReportTypes;
    public $optionFilingTypes;
    public $optionPaymentTypes;
    public $showPreviewTable = false;
    public $activateButtons = false;
    public $subVatOptions = [];

    public $tax_type_id = 'all';
    public $tax_type_code = 'all';
    public $type = 'Filing';
    public $filing_report_type = 'All-Filings';
    public $payment_report_type;
    public $range_start;
    public $range_end;
    public $vat_type;
    public $showMoreFilters = false;
    public $hasData;
    public $today;

    //extra filters
    public $optionTaxRegions = [];
    public $selectedTaxReginIds=[];
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
            'vat_type' => $this->tax_type_code == 'vat' ? 'required' : '',
            'range_start' => 'required',
            'range_end' => 'required',
            'filing_report_type' => $this->type == 'Filing' ? 'required' : '',
            'payment_report_type' => $this->type == 'Payment' ? 'required' : '',
        ];
    }

    public function mount()
    {
        $this->today = date('Y-m-d');
        $this->range_start = $this->today;
        $this->range_end = $this->today;
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

        $this->parameters = $this->getParameters();

    }

    public function updated($propertyName)
    {
        if ($propertyName == 'tax_type_id') {
            if($this->tax_type_id != 'all'){
                $this->tax_type_code = TaxType::findOrFail($this->tax_type_id)->code;

                if($this->tax_type_code == TaxType::VAT) {
                    $this->subVatOptions = SubVat::select('id', 'name')->get();
                }
            }else{
                $this->tax_type_code = 'all';
            }
            $this->reset('vat_type');
        }

        if ($propertyName == 'type') {
            $this->reset('filing_report_type', 'payment_report_type');
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

    //preview report
    public function preview()
    {
        // dd($this->getParameters());
        $this->validate();
        if (!$this->checkCheckboxes()) {
            return;
        };
        $this->parameters = $this->getParameters();
        $this->previewReport($this->parameters);
    }

    //export pdf report
    public function exportPdf()
    {
        $this->validate();
        $this->parameters = $this->getParameters();
        $this->exportPdfReport($this->parameters);
    }

    //export excel report
    public function exportExcel()
    {
        $this->validate();
        $this->parameters = $this->getParameters();
        $this->exportExcelReport($this->parameters);
    }


    public function getParameters()
    {
        return [
            'tax_type_id' => $this->tax_type_id ?? 'all',
            'tax_type_code' => $this->tax_type_id == 'all' ? 'all' : TaxType::findOrFail($this->tax_type_id)->code,
            'tax_type_name' => $this->tax_type_id == 'all' ? 'All Tax Types Returns' : TaxType::findOrFail($this->tax_type_id)->name,
            'vat_type' => $this->vat_type,
            'type' => $this->type,
            'filing_report_type' => $this->filing_report_type,
            'payment_report_type' => $this->payment_report_type,
            'tax_regions' => array_keys($this->removeItemsOnFalse($this->selectedTaxReginIds)),
            'region' => $this->region,
            'district' => $this->district,
            'ward' => $this->ward,
            'range_start' => $this->range_start,
            'range_end' =>  $this->range_end,
        ];
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
            $this->customAlert('error', 'Select Atleast one Tax Region');
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
