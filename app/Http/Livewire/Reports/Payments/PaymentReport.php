<?php

namespace App\Http\Livewire\Reports\Payments;

use App\Enum\CustomMessage;
use App\Enum\ReportStatus;
use App\Models\District;
use App\Models\FinancialYear;
use App\Models\Region;
use App\Models\TaxRegion;
use App\Models\TaxType;
use App\Models\Ward;
use App\Traits\CustomAlert;
use App\Traits\GenericReportTrait;
use App\Traits\PaymentReportTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Component;

class PaymentReport extends Component
{

    use CustomAlert, PaymentReportTrait, GenericReportTrait;

    public $optionYears = [];
    public $optionPeriods = [];
    public $optionSemiAnnuals = [];
    public $optionQuarters = [];
    public $optionMonths = [];
    public $optionTaxTypes = [];
    public $optionReportTypes = [];
    public $optionFilingTypes = [];
    public $optionPaymentReportTypes = [];
    public $showPreviewTable = false;
    public $activateButtons = false;
    public $optionVatTypes = [];

    public $payment_category;
    public $year = ReportStatus::all;
    public $month;
    public $period;
    public $quater;
    public $semiAnnual;
    public $tax_type_id = ReportStatus::all;
    public $tax_type_code = ReportStatus::all;
    public $status;
    public $filing_report_type = 'All-Filings';
    public $payment_report_type;
    public $range_start;
    public $range_end;
    public $vat_type = 'All-VAT-Returns';
    public $reportType;
    public $optionPaymentTypes = [];

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
    public $previewData;
    public $isReturn = false;
    public $isConsultant = false;

    protected function rules()
    {
        return [
            'payment_category' => 'required|alpha_gen',
            'tax_type_id' => $this->payment_category == 'returns' ? 'required|alpha_gen' : 'nullable',
            'year' => 'required|alpha_num',
            'vat_type' => $this->tax_type_code == 'vat' ? 'required|alpha_gen' : 'nullable',
            'range_start' => $this->year == ReportStatus::RANGE ? 'required|date' : 'nullable',
            'range_end' => $this->year == ReportStatus::RANGE ? 'required|date' : 'nullable',
            'filing_report_type' => $this->status == 'Filing' ? ['required', 'alpha_gen'] : 'nullable',
            'payment_report_type' => $this->status == 'Payment' ? ['required', 'alpha_gen'] : 'nullable',
            'period' => $this->year != ReportStatus::all && $this->year != 'range' ? ['required', Rule::in([ReportStatus::MONTHLY, ReportStatus::QUARTERLY, ReportStatus::SEMI_ANNUAL, ReportStatus::ANNUAL])] : 'nullable',
            'month' => $this->period == ReportStatus::MONTHLY ? ['required', 'alpha_gen'] : 'nullable',
            'quater' => $this->period == ReportStatus::QUARTERLY ? ['required', Rule::in([ReportStatus::FIRST_QUARTER, ReportStatus::SECOND_QUARTER, ReportStatus::THIRD_QUARTER, ReportStatus::FOURTH_QUARTER])] : '',
            'semiAnnual' => $this->period == ReportStatus::SEMI_ANNUAL ? 'required|alpha_gen' : 'nullable',
        ];
    }

    public function mount()
    {
        $this->optionTaxTypes = TaxType::where('category', ReportStatus::TAX_TYPE_CAT_MAIN)->select('id', 'name')->orderBy('name')->get();

        $this->initializeOptions();

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
    }

    public function updated($propertyName)
    {
        try {
            if ($propertyName == ReportStatus::TAX_TYPE_ID) {
                if ($this->tax_type_id != ReportStatus::all) {
                    $this->tax_type_code = TaxType::findOrFail($this->tax_type_id)->code;
                    if (is_null($this->tax_type_code)) {
                        abort(404);
                    }
                } else {
                    $this->tax_type_code = ReportStatus::all;
                }
                $this->reset('vat_type');
            }

            if ($propertyName == 'period') {
                $this->reset('month', 'quater', 'semiAnnual');
            }

            if ($propertyName == 'year') {
                $this->reset('month', 'quater', 'semiAnnual', 'period');
            }

            if ($propertyName == 'status') {
                $this->reset('month', 'quater', 'semiAnnual', 'period', 'year');
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
            Log::error('REPORTS-PAYMENTS-PAYMENT-REPORT-UPDATED', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }

    }

    //preview report
    public function preview()
    {
        $this->validate();

        try {
            if (!$this->checkCheckboxes()) {
                return;
            };
            $this->parameters = $this->getParameters();
            $records = $this->getRecords($this->parameters)->limit(5)->get();
            if ($records->count() < 1) {
                $this->customAlert('error', 'No Records Found in the selected criteria');
                return;
            }
            if (isset($this->parameters['payment_category']) && $this->parameters['payment_category'] == 'returns') {
                $this->previewData = $records;
                $this->isReturn = true;
            } else {
                $this->previewData = $records;
                $this->isConsultant = true;
            }
        } catch (\Exception $exception) {
            Log::error('REPORTS-PAYMENTS-PAYMENT-REPORT-PREVIEW', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }

    }

    //export pdf report
    public function exportPdf()
    {
        $this->validate();
        try {
            $this->parameters = $this->getParameters();
            $this->exportPdfReport($this->parameters);
        } catch (\Exception $exception) {
            Log::error('REPORTS-PAYMENTS-PAYMENT-REPORT-EXPORT-PDF', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }
    }

    //export excel report
    public function exportExcel()
    {
        $this->validate();
        try {
            $this->parameters = $this->getParameters();
            $this->exportExcelReport($this->parameters);
        } catch (\Exception $exception) {
            Log::error('REPORTS-PAYMENTS-PAYMENT-REPORT-EXPORT-EXCEL', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }

    }


    public function getParameters()
    {
        return [
            'payment_category' => $this->payment_category,
            'tax_type_id' => $this->tax_type_id ?? ReportStatus::all,
            'tax_type_code' => $this->tax_type_id == ReportStatus::all ? ReportStatus::all : TaxType::find($this->tax_type_id)->code ?? 'N/A',
            'tax_type_name' => $this->tax_type_id == ReportStatus::all ? 'All Tax Types Returns' : TaxType::find($this->tax_type_id)->name ?? 'N/A',
            'vat_type' => $this->vat_type,
            'status' => $this->status,
            'year' => $this->year,
            'period' => $this->period,
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
        try {
            if ($this->year == ReportStatus::all) {
                return [
                    'startDate' => null,
                    'endDate' => null,
                ];
            } elseif ($this->year == ReportStatus::RANGE) {
                return [
                    'startDate' => date('Y-m-d', strtotime($this->range_start)),
                    'endDate' => date('Y-m-d', strtotime($this->range_end)),
                    'from' => date('Y-m-d 00:00:00', strtotime($this->range_start)),
                    'end' => date('Y-m-d 23:59:59', strtotime($this->range_end)),
                ];
            } elseif ($this->month >= 1 & $this->month <= 12) {
                $date = \Carbon\Carbon::parse($this->year . "-" . $this->month . "-01");
                $start = $date->startOfMonth()->format('Y-m-d H:i:s');
                $end = $date->endOfMonth()->format('Y-m-d H:i:s');
                $from = $date->startOfMonth()->format('Y-m-d');
                $to = $date->endOfMonth()->format('Y-m-d');
                return ['startDate' => $start, 'endDate' => $end, 'from' => $from, 'to' => $to];
            } elseif ($this->quater) {
                if ($this->quater == ReportStatus::FIRST_QUARTER) {
                    $this->startMonth = ReportStatus::ONE;
                    $this->endMonth = ReportStatus::THREE;
                } elseif ($this->quater == ReportStatus::SECOND_QUARTER) {
                    $this->startMonth = ReportStatus::FOUR;
                    $this->endMonth = ReportStatus::SIX;
                } elseif ($this->quater == ReportStatus::THIRD_QUARTER) {
                    $this->startMonth = ReportStatus::SEVEN;
                    $this->endMonth = ReportStatus::NINE;
                } elseif ($this->quater == ReportStatus::FOURTH_QUARTER) {
                    $this->startMonth = ReportStatus::TEN;
                    $this->endMonth = ReportStatus::TWELVE;
                }

                $startDate = \Carbon\Carbon::parse($this->year . "-" . $this->startMonth . "-01");
                $endDate = \Carbon\Carbon::parse($this->year . "-" . $this->endMonth . "-01");
                $start = $startDate->startOfMonth()->format('Y-m-d H:i:s');
                $end = $endDate->endOfMonth()->format('Y-m-d H:i:s');
                $from = $startDate->format('Y-m-d');
                $to = $endDate->format('Y-m-d');
                return ['startDate' => $start, 'endDate' => $end, 'from' => $from, 'to' => $to];
            } elseif ($this->semiAnnual) {
                if ($this->semiAnnual == ReportStatus::FIRST_SEMI_ANNUAL) {
                    $this->startMonth = ReportStatus::ONE;
                    $this->endMonth = ReportStatus::SIX;
                } elseif ($this->semiAnnual == ReportStatus::SECOND_SEMI_ANNUAL) {
                    $this->startMonth = ReportStatus::SEVEN;
                    $this->endMonth = ReportStatus::TWELVE;
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
        } catch (\Exception $exception) {
            throw $exception;
        }

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
        return view('livewire.reports.payments.payment-report');
    }
}
