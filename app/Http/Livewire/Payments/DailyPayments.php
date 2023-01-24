<?php

namespace App\Http\Livewire\Payments;

use App\Exports\DailyPaymentExport;
use App\Traits\DailyPaymentTrait;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class DailyPayments extends Component
{
    use LivewireAlert;
    use DailyPaymentTrait;

    public $today;
    public $range_start;
    public $range_end;

    public $taxTypes;
    public $optionTaxRegions;
    public $tax_region_id;
    public $vars;

    protected $rules =[
        'range_start'=>'required|strip_tag',
        'range_end' => 'required|strip_tag',
    ];

    public function mount()
    {
        $this->today = date('Y-m-d');
        $this->range_start = date('Y-m-d');
        $this->range_end = date('Y-m-d');
        $this->getData();
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
        return view('livewire.payments.daily-payments');
    }
}
