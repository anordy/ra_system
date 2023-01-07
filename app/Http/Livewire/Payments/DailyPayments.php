<?php

namespace App\Http\Livewire\Payments;

use App\Models\TaxType;
use App\Models\ZmPayment;
use Carbon\Carbon;
use Livewire\Component;
use PDF;

class DailyPayments extends Component
{
    public $taxTypes;
    public $todayTzsTotalCollection;
    public $todayUsdTotalCollection;
    public $monthUsdTotalCollection;
    public $monthTzsTotalCollection;

    public function mount()
    {
        $this->taxTypes = TaxType::whereIn('id', function ($query) {
            $query->select('zm_bills.tax_type_id')
                ->from('zm_payments')
                ->leftJoin('zm_bills', 'zm_payments.zm_bill_id', 'zm_bills.id')
                ->whereBetween('zm_payments.trx_time', [Carbon::today()->firstOfMonth(), Carbon::today()->endOfDay()])
                ->distinct();
        })->get();

        $this->todayTzsTotalCollection = ZmPayment::where('currency','TZS')
                ->whereDate('trx_time', [Carbon::today()])
                ->sum('paid_amount');

        $this->todayUsdTotalCollection = ZmPayment::where('currency','USD')
                ->whereDate('trx_time', [Carbon::today()])
                ->sum('paid_amount');

        $this->monthTzsTotalCollection = ZmPayment::where('currency','TZS')
                ->whereBetween('trx_time', [Carbon::today()->firstOfMonth(), Carbon::today()->endOfDay()])
                ->sum('paid_amount');

        $this->monthUsdTotalCollection = ZmPayment::where('currency','USD')
                ->whereBetween('trx_time', [Carbon::today()->firstOfMonth(), Carbon::today()->endOfDay()])
                ->sum('paid_amount');
    }

    public function downloadPdf(){
        $fileName = 'daily_payments_' . now()->format('d-m-Y') . '.pdf';
        $vars['taxTypes'] = $this->taxTypes;
        $vars['todayTzsTotalCollection'] = $this->todayTzsTotalCollection;
        $vars['todayUsdTotalCollection'] = $this->todayUsdTotalCollection;
        $vars['monthTzsTotalCollection'] = $this->monthTzsTotalCollection;
        $vars['monthUsdTotalCollection'] = $this->monthUsdTotalCollection;

        $pdf = PDF::loadView('exports.payments.pdf.daily-payments', compact('vars'));
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $fileName
        );
    }

    public function render()
    {
        return view('livewire.payments.daily-payments');
    }
}
