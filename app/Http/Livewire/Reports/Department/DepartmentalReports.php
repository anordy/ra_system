<?php

namespace App\Http\Livewire\Reports\Department;

use App\Exports\DailyPaymentExport;
use App\Models\Region;
use App\Models\Returns\Vat\SubVat;
use App\Models\TaxRegion;
use App\Models\TaxType;
use App\Traits\DailyPaymentTrait;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class DepartmentalReports extends Component
{
    use LivewireAlert, DailyPaymentTrait;

    public $today;
    public $range_start;
    public $range_end;

    public $taxTypes;
    public $tax_region_id;
    public $vars;

    public $location = 'unguja';
    public $pemba_tax_region;

    public $department_type = 'large-taxpayer';

    public $optionsReportTypes = [];
    public $optionTaxTypes;
    public $tax_type_id = 'all';
    public $tax_type_code = 'all';
    public $subVatOptions = [];
    public $vat_type;
    public $optionTaxTypeOthers = [];
    public $optionTaxRegions = [];
    public $selectedTaxReginIds = [];
    public $non_tax_revenue_selected = 'all';


    protected $rules =[
        'range_start'=>'required|strip_tag',
        'range_end' => 'required|strip_tag',
    ];

    public function mount()
    {
        $this->today = date('Y-m-d');
        $this->range_start = date('Y-m-d');
        $this->range_end = date('Y-m-d');

        $this->optionsReportTypes = ['large-taxpayer' => 'Large Taxpayer Department', 'domestic-taxes' => 'Domestic Taxes Department', 'non-tax-revenue' => 'Non-Tax Revenue Department'];
        $this->optionTaxTypes = TaxType::where('category', 'main')->get();
        $this->optionTaxTypeOthers = ['airport_service_charge'=>'Airport Service Charge', 'road_license_fee'=>'Road License Fee', 'airport_service_charge'=>'Airport Service Charge', 'seaport_service_charge'=>'Seaport Service Charge', 'seaport_transport_charge'=>'Seaport Transport Charge'];

        $this->optionTaxRegions = TaxRegion::query()->select('name', 'id')->where('location', Region::UNGUJA)->get()->pluck('name', 'id');
        $this->selectedTaxReginIds = $this->optionTaxRegions;

        $this->getData();
    }

    public function updated($propertyName){
        $this->search();


        if ($propertyName == 'tax_type_id') {
            if ($this->tax_type_id != 'all') {
                $this->tax_type_code = TaxType::findOrFail($this->tax_type_id)->code;

                if ($this->tax_type_code == TaxType::VAT) {
                    $this->subVatOptions = SubVat::select('id', 'name')->get();
                }
            }
            $this->reset('vat_type');
        }
    }

    public function search()
    {
        $this->validate();
        $this->getData();
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
            $this->alert('error', 'Something went wrong, please contact the administrator for help');
            Log::error($e);
        }
    }

    public function downloadExcel()
    {
        $fileName = 'daily_payments_' . now()->format('d-m-Y') . '.xlsx';
        $title = 'Daily Receipts Provisional';
        $this->alert('success', 'Exporting Excel File');
        return Excel::download(new DailyPaymentExport($this->vars,$this->taxTypes,$title), $fileName);
    }


    public function getData()
    {
        $this->taxTypes = $this->getInvolvedTaxTypes($this->range_start,$this->range_end);

        $this->vars['tzsTotalCollection'] = $this->getTotalCollectionPerCurrency('TZS',$this->range_start,$this->range_end);

        $this->vars['usdTotalCollection'] = $this->getTotalCollectionPerCurrency('USD',$this->range_start,$this->range_end);

        $this->vars['range_start'] = $this->range_start;

        $this->vars['range_end'] = $this->range_end;
    }

    public function render()
    {
        return view('livewire.reports.department.departmental-reports');
    }
}
