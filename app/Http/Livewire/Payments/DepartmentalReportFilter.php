<?php

namespace App\Http\Livewire\Payments;

use App\Models\District;
use App\Models\Region;
use App\Models\Returns\Vat\SubVat;
use App\Models\TaxRegion;
use App\Models\TaxType;
use App\Models\Ward;
use App\Traits\DepartmentalReportTrait;
use App\Traits\PaymentReportTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DepartmentalReportFilter extends Component
{
    use LivewireAlert;
    use DepartmentalReportTrait;

    public $optionTaxTypes;
    public $optionReportTypes;
    public $optionsReportTypes;
    public $showPreviewTable = false;
    public $activateButtons = false;
    public $subVatOptions = [];
    public $optionTaxTypeOthers;

    public $department_type = 'large-taxpayer';
    public $tax_type_id = 'all';
    public $tax_type_code = 'all';
    public $non_tax_revenue_selected = 'all';
    public $payment_status='all';
    public $range_start;
    public $range_end;
    public $vat_type;
    public $hasData;
    public $today;

    //extra filters
    public $optionTaxRegions = [];
    public $selectedTaxReginIds = [];
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
            'vat_type' => $this->tax_type_code == 'vat' ? 'required' : '',
            'range_start' => 'required',
            'range_end' => 'required',
            'payment_status' => 'required',
        ];
    }

    public function mount()
    {
        $this->today = date('Y-m-d');
        $this->range_start = $this->today;
        $this->range_end = $this->today;
        $this->optionTaxTypes = TaxType::where('category', 'main')->get();
        $this->optionsReportTypes = ['large-taxpayer' => 'Large Taxpayer Department', 'domestic-taxes' => 'Domestic Taxes Department', 'non-tax-revenue' => 'Non-Tax Revenue Department', 'pemba' => 'Pemba'];
        $this->optionTaxTypeOthers = ['airport_service_charge'=>'Airport Service Charge', 'road_license_fee'=>'Road License Fee', 'airport_service_charge'=>'Airport Service Charge', 'seaport_service_charge'=>'Seaport Service Charge', 'seaport_transport_charge'=>'Seaport Transport Charge'];
        //extra filters
        $this->optionTaxRegions = TaxRegion::pluck('name', 'id')->toArray();
        $this->selectedTaxReginIds = $this->optionTaxRegions;
        $this->regions = Region::select('id', 'name')->get();

        $this->districts = [];
        $this->wards = [];

        $this->region = 'all';
        $this->district = 'all';
        $this->ward = 'all';

        $this->parameters = $this->getParameters();
    }

    public function updated($propertyName)
    {
        if ($this->department_type == 'pemba') {
            $this->regions = Region::select('id', 'name')->where('location', 'pemba')->get();
        } else {
            $this->regions = Region::select('id', 'name')->get();
        }


        if ($propertyName == 'tax_type_id') {
            if ($this->tax_type_id != 'all') {
                $this->tax_type_code = TaxType::findOrFail($this->tax_type_id)->code;

                if ($this->tax_type_code == TaxType::VAT) {
                    $this->subVatOptions = SubVat::select('id', 'name')->get();
                }
            } 
            $this->reset('vat_type');
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

        $this->preview();
    }

    //preview report
    public function preview()
    {
        $this->validate();
        if (!$this->checkCheckboxes()) {
            return;
        };
        $this->parameters = $this->getParameters();
        $records = $this->getRecords($this->parameters)->get();
        if($records->count() > 0){
            $this->hasData = true;
        }else{
            $this->hasData = false;
        }
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
        $parameters= [
            'department_type' => $this->department_type,
            'tax_type_id' =>  $this->tax_type_id ?? 'all',
            'tax_type_code' => $this->tax_type_id == 'all' ? 'all' : TaxType::findOrFail($this->tax_type_id)->code,
            'tax_type_name' => $this->tax_type_id == 'all' ? 'All Tax Types Returns' : TaxType::findOrFail($this->tax_type_id)->name,
            'tax_type_name' => $this->tax_type_id == 'all' ? 'All Tax Types Returns' : TaxType::findOrFail($this->tax_type_id)->name,
            'vat_type' => $this->vat_type,
            'payment_status' => $this->payment_status,
            'tax_regions' => array_keys($this->removeItemsOnFalse($this->selectedTaxReginIds)),
            'region' => $this->region,
            'district' => $this->district,
            'ward' => $this->ward,
            'range_start' => $this->range_start,
            'range_end' =>  $this->range_end,
        ];

        if($this->department_type == 'non-tax-revenue'){
            $parameters['tax_type_id'] = 'not-applicable';
            $parameters['tax_type_code'] = 'not-applicable';
            $parameters['tax_type_name'] = 'not-applicable';

            if($this->non_tax_revenue_selected == 'all'){
                $parameters['non_tax_revenue_selected'] = 'all';
                $parameters['non_tax_revenue_ids'] = array_keys($this->optionTaxTypeOthers);
            }else{
                $parameters['non_tax_revenue_selected'] = $this->non_tax_revenue_selected;
                $parameters['non_tax_revenue_ids'] = 'not-applicable';
            }
        }else{
            $parameters['non_tax_revenue_selected'] = 'not-applicable';
            $parameters['non_tax_revenue_ids'] = 'not-applicable';
        }

        return $parameters;
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

    public function render()
    {
        return view('livewire.payments.departmental-reports-filter');
    }
}
