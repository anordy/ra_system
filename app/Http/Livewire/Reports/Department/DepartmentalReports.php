<?php

namespace App\Http\Livewire\Reports\Department;

use App\Exports\Departmental\DepartmentalReportExport;
use App\Models\Business;
use App\Models\Region;
use App\Models\Returns\Vat\SubVat;
use App\Models\TaxRegion;
use App\Models\TaxType;
use App\Models\ZmBill;
use App\Traits\DailyPaymentTrait;
use App\Traits\ManagerialReportTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class DepartmentalReports extends Component
{
    use CustomAlert, DailyPaymentTrait, ManagerialReportTrait;

    public $today;
    public $range_start;
    public $range_end;

    public $taxTypes;
    public $tax_region_id;
    public $vars;

    public $location = 'all';

    public $department_type = 'large-taxpayer';

    public $optionsReportTypes = [];
    public $optionTaxTypes;
    public $tax_type_id = 'all';
    public $tax_type_code = 'all';
    public $subVatOptions = [];
    public $vat_type;
    public $nonRevenueTaxTypes = [];
    public $taxRegions = [];
    public $selectedTaxReginIds = [];

    public $domesticTaxTypes = [];
    public $filteringForLto = false;
    public $report;

    protected $rules =[
        'range_start'=>'required|strip_tag',
        'range_end' => 'required|strip_tag',
    ];

    public function mount()
    {
        $this->today = date('Y-m-d');
        $this->range_start = date('Y-m-d');
        $this->range_end = date('Y-m-d');

        $this->optionsReportTypes = [
            'large-taxpayer' => 'Large Taxpayer Department',
            'domestic-taxes' => 'Domestic Taxes Department',
            'non-tax-revenue' => 'Non-Tax Revenue Department'
        ];

        $this->optionTaxTypes = TaxType::where('category', 'main')->get();
        $this->nonRevenueTaxTypes = TaxType::query()
            ->select(['id', 'name'])
            ->where('category', 'other')
            ->whereIn('code', [
                TaxType::AIRPORT_SERVICE_CHARGE,
                TaxType::SEAPORT_TRANSPORT_CHARGE,
                TaxType::AIRPORT_SAFETY_FEE,
                TaxType::SEAPORT_SERVICE_CHARGE,
                TaxType::ROAD_LICENSE_FEE,
                TaxType::INFRASTRUCTURE, TaxType::RDF
            ])
            ->get();

        $this->domesticTaxTypes = TaxType::query()
            ->select(['id', 'name'])
            ->where('category', 'main')
            ->whereNotIn('code', [
                TaxType::AIRPORT_SERVICE_SAFETY_FEE,
                TaxType::SEAPORT_SERVICE_TRANSPORT_CHARGE
            ])
            ->get();

        $this->taxRegions = TaxRegion::query()->select('name', 'id')->get()->pluck('name', 'id');
        $this->selectedTaxReginIds = $this->taxRegions;

        $this->getReport();
    }

    public function updated($propertyName){

        if ($propertyName == 'tax_type_id') {
            if ($this->tax_type_id != 'all') {
                $this->tax_type_code = TaxType::findOrFail($this->tax_type_id)->code;

                if ($this->tax_type_code == TaxType::VAT) {
                    $this->subVatOptions = SubVat::select('id', 'name')->get();
                }
            }
            $this->reset('vat_type');
        }

        if ($propertyName == 'location'){
            $query = TaxRegion::query()->select('name', 'id');

            if ($this->location != 'all'){
                $query->where('location', $this->location);
            }

            $this->taxRegions = $query->get()->pluck('name', 'id');

            $this->selectedTaxReginIds = $this->taxRegions;

            // LTD
            if ($this->location == Region::UNGUJA && $this->department_type == 'large-taxpayer'){
                $this->filteringForLto = true;
            } else {
                $this->filteringForLto = false;
            }
        }

        if ($propertyName == 'department_type'){
            $this->selectedTaxReginIds = $this->taxRegions;

            // LTD
            if ($this->location == Region::UNGUJA && $this->department_type == 'large-taxpayer'){
                $this->filteringForLto = true;
            } else {
                $this->filteringForLto = false;
            }
        }

        $this->search();

    }

    public function search()
    {
        $this->validate();
        $this->getReport();
    }


    public function downloadPdf()
    {
        try{
            $fileName = 'daily_payments_' . now()->format('d-m-Y') . '.pdf';
            $pdf = PDF::loadView('exports.payments.pdf.daily-payments', ['vars'=>$this->vars,'taxTypes'=>$this->taxTypes]);
            $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

            return response()->streamDownload(
                fn () => print($pdf->output()), $fileName
            );
        }catch(Exception $e){
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            Log::error($e);
        }
    }

    public function exportExcel()
    {
        $fileName = 'departmental_report_' . now()->format('d-m-Y') . '.xlsx';
        $title = 'Managerial Departmental Report';
        $this->customAlert('success', 'Exporting Excel File');
        return Excel::download(new DepartmentalReportExport($this->report, $title, $this->nonRevenueTaxTypes, $this->domesticTaxTypes, [
            'range_start' => $this->range_start,
            'range_end' => $this->range_end,
            'location' => $this->location,
            'departmentType' => $this->department_type
        ]), $fileName);
    }

    protected function getReport($currency = 'USD'){
        $this->report['USD'] = $this->queryData('USD');
        $this->report['TZS'] = $this->queryData('TZS');
    }

    public function queryData($currency){
        $taxRegionsIds = array_keys($this->selectedTaxReginIds->toArray());
        $filteringForLto = $this->filteringForLto;

        $queryDTD = ZmBill::query()
            ->rightJoin('zm_bill_items', 'zm_bills.id', 'zm_bill_items.zm_bill_id')
            ->select(['zm_bills.tax_type_id as tax_type_id', DB::raw('sum(zm_bill_items.amount) as item_amount')])
            ->groupBy(['zm_bills.tax_type_id'])
            ->whereNotNull(['zm_bill_items.billable_id', 'zm_bill_items.billable_id'])
            ->whereIn('zm_bills.tax_type_id', $this->domesticTaxTypes->pluck('id'))
            //     columns
            ->whereHas('billable', function ($query) use ($taxRegionsIds, $filteringForLto, $currency){
                $query->whereIn('location_id', $taxRegionsIds);

                // If filtering for LTO
                if ($filteringForLto){
                    $query->whereIn('business_id', Business::query()
                        ->where('is_business_lto', true)
                        ->select('id')
                        ->get()
                        ->toArray());
                }

                // Filter currencies
                $query->where('currency', $currency);

                // Filter Dates
                $query->whereDate('paid_at', '>=', $this->range_start);
                $query->whereDate('tax_returns.created_at', '<=', $this->range_end);
            })
            ->with('billable', 'billable.business');

        $queryNTR = ZmBill::query()
            ->rightJoin('zm_bill_items', 'zm_bills.id', 'zm_bill_items.zm_bill_id')
            ->select(['zm_bill_items.tax_type_id as tax_type_id', DB::raw('sum(zm_bill_items.amount) as item_amount')])
            ->groupBy(['zm_bill_items.tax_type_id'])
            ->whereNotNull(['zm_bill_items.billable_id', 'zm_bill_items.billable_id'])
            ->whereIn('zm_bill_items.tax_type_id', $this->nonRevenueTaxTypes->pluck('id'))
            //     columns
            ->whereHas('billable', function ($query) use ($taxRegionsIds, $filteringForLto, $currency){
                $query->whereIn('location_id', $taxRegionsIds);

                // If filtering for LTO
                if ($filteringForLto){
                    $query->whereIn('business_id', Business::query()
                        ->where('is_business_lto', true)
                        ->select('id')
                        ->get()
                        ->toArray());
                }

                // Filter currency
                $query->where('currency', $currency);

                // Filter Dates
                $query->whereDate('paid_at', '>=', $this->range_start);
                $query->whereDate('tax_returns.created_at', '<=', $this->range_end);
            })
            ->with('billable', 'billable.business');

        $report = [];

        foreach ($queryDTD->get() as $item) {
            $report[$item->tax_type_id] = $item->item_amount;
        }

        foreach ($queryNTR->get() as $item) {
            $report[$item->tax_type_id] = $item->item_amount;
        }

        return $report;
    }
    protected function getInvolvedTaxes($starts, $ends, $location, $regions, $department){
        $this->start = Carbon::parse($starts)->startOfDay()->toDateTimeString();
        $this->end = Carbon::parse($ends)->endOfDay()->toDateTimeString();

        $query = TaxType::whereIn('id', function ($query) {
            $query->select('zm_bills.tax_type_id')
                ->from('zm_payments')
                ->leftJoin('zm_bills', 'zm_payments.zm_bill_id', 'zm_bills.id')
                ->whereBetween('zm_payments.trx_time', [$this->start, $this->end])
                ->distinct();
        })->query();
    }

    public function render()
    {
        return view('livewire.reports.department.departmental-reports');
    }
}
