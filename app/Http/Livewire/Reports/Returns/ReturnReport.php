<?php

namespace App\Http\Livewire\Reports\Returns;

use App\Enum\CustomMessage;
use App\Enum\ReportStatus;
use App\Exports\ReturnReportExport;
use App\Models\District;
use App\Models\Region;
use App\Models\Returns\Vat\SubVat;
use App\Models\TaxRegion;
use App\Models\TaxType;
use App\Models\Ward;
use App\Traits\CustomAlert;
use App\Traits\ReturnReportTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Component;
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
            'tax_type_id' => 'required|alpha_num',
            'type' => ['required', Rule::in($this->optionReportTypes)],
            'vat_type' => $this->tax_type_code == 'vat' ? ['required', 'alpha_num'] : 'nullable',
            'range_start' => 'required|date',
            'range_end' => 'required|date',
            'filing_report_type' => $this->type == ReportStatus::FILING ? ['required', Rule::in($this->optionFilingTypes)] : 'nullable',
            'payment_report_type' => $this->type == ReportStatus::PAYMENT ? ['required', Rule::in($this->optionPaymentTypes)] : 'nullable',
            'selectedTaxReginIds' => ['sometimes', 'array'],
            'region' => ['sometimes', 'alpha_num'],
            'district' => ['sometimes', 'alpha_num'],
            'ward' => ['sometimes', 'alpha_num'],
        ];
    }

    public function mount()
    {
        $this->today = date('Y-m-d');
        $this->range_start = $this->today;
        $this->range_end = $this->today;
        $this->optionTaxTypes = TaxType::where('category', ReportStatus::TAX_TYPE_CAT_MAIN)->get();
        $this->optionReportTypes = ReportStatus::RETURN_REPORT_TYPES;
        $this->optionFilingTypes = ReportStatus::RETURN_FILLING_TYPES;
        $this->optionPaymentTypes = ReportStatus::RETURN_PAYMENT_TYPES;

        //extra filters
        $this->optionTaxRegions = TaxRegion::pluck('name', 'id')->toArray();
        $this->selectedTaxReginIds = $this->optionTaxRegions;
        $this->regions = Region::select('id', 'name')->get();
        $this->districts = [];
        $this->wards = [];

        $this->region = ReportStatus::all;
        $this->district = ReportStatus::all;
        $this->ward = ReportStatus::all;

        //toggle filter
        $this->showMoreFilters = false;

        $this->parameters = $this->getParameters();

    }

    public function updated($propertyName)
    {
        try {
            if ($propertyName == ReportStatus::TAX_TYPE_ID) {
                if ($this->tax_type_id != ReportStatus::all) {
                    $this->tax_type_code = TaxType::findOrFail($this->tax_type_id)->code;

                    if ($this->tax_type_code == TaxType::VAT) {
                        $this->subVatOptions = SubVat::select('id', 'name')->get();
                    }
                } else {
                    $this->tax_type_code = ReportStatus::all;
                }
                $this->reset('vat_type');
            }

            if ($propertyName == ReportStatus::TYPE) {
                $this->reset('filing_report_type', 'payment_report_type');
            }

            //Physical Location
            if ($propertyName === ReportStatus::REGION) {
                $this->wards = [];
                $this->districts = [];
                if ($this->region != ReportStatus::all) {
                    $this->districts = District::where('region_id', $this->region)->select('id', 'name')->get();
                }
                $this->ward = ReportStatus::all;
                $this->district = ReportStatus::all;
            }
            if ($propertyName === ReportStatus::DISTRICT) {
                $this->wards = [];
                if ($this->district != ReportStatus::all) {
                    $this->wards = Ward::where('district_id', $this->district)->select('id', 'name')->get();
                }
                $this->ward = ReportStatus::all;
            }
        } catch (\Exception $exception) {
            Log::error('REPORTS-RETURNS-RETURN-REPORT-UPDATED', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }

    }

    //preview report
    public function preview()
    {
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
        try {
            $this->parameters = $this->getParameters();
            $this->exportPdfReport($this->parameters);
        } catch (\Exception $exception) {
            Log::error('REPORTS-RETURNS-RETURN-REPORT-EXPORT-PDF', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }
    }

    //export excel report
    public function exportExcel()
    {
        $this->validate();
        try {
            $this->parameters = $this->getParameters();
            $records = $this->getRecords($this->parameters);

            if ($records->count() < 1) {
                $this->customAlert('error', 'No Records Found in the selected criteria');
                return;
            }

            if (!isset($this->parameters['tax_type_name']) && !isset($this->parameters['filing_report_type'])) {
                throw new \Exception('Missing tax_type_name or filing_report_type keys in parameters');
            }

            $fileName = $this->parameters['tax_type_name'] . '_' . $this->parameters['filing_report_type'] . ' - ' . '.xlsx';
            $title = $this->parameters['filing_report_type'] . ' For' . $this->parameters['tax_type_name'];
            $this->customAlert('success', 'Exporting Excel File');
            return Excel::download(new ReturnReportExport($records, $title, $this->parameters), $fileName);
        } catch (\Exception $exception) {
            Log::error('REPORTS-RETURNS-RETURN-REPORT-EXPORT-EXCEL', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }
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
            'range_end' => $this->range_end,
        ];
    }

    public function checkCheckboxes()
    {
        //tax regions
        $taxRegionSeletected = false;
        foreach ($this->selectedTaxReginIds as $value) {
            if (!$value) {
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
