<?php

namespace App\Http\Livewire\Payments;

use App\Enum\GeneralConstant;
use App\Models\PBZTransaction;
use App\Models\ZmBill;
use App\Traits\CustomAlert;
use Carbon\Carbon;
use Livewire\Component;
use PDF;

class PBZPaymentFilter extends Component
{
    use CustomAlert;

    public $tableName;
    public $data;
    public $currency = GeneralConstant::ALL;
    public $range_start;
    public $range_end;
    public $status;
    public $has_bill;

    protected $rules = [
        'range_start' => 'required|strip_tag',
        'range_end' => 'required|strip_tag'
    ];

    public function mount($tablename)
    {
        $this->tableName = $tablename;
        $this->range_start = Carbon::now()->startOfMonth()->toDateString();
        $this->range_end = date('Y-m-d', strtotime(now()));
    }

    public function filter()
    {
        $this->validate();

        $filters = [
            'currency'    => $this->currency,
            'range_start' => date('Y-m-d 00:00:00', strtotime($this->range_start)),
            'range_end'   => date('Y-m-d 23:59:59', strtotime($this->range_end)),
            'has_bill' => $this->has_bill
        ];

        $this->data = $filters;

        if ($this->tableName == 'p-b-z-reversals-table') {
            $this->emitTo('payments.p-b-z-reversals-table', 'filterData', $filters);
        } elseif ($this->tableName == 'p-b-z-payments-table') {
            $this->emitTo('payments.p-b-z-payments-table', 'filterData', $filters);
        }
    }

    public function pdf()
    {
        $this->filter();

        $data   = $this->data;
        $query = (new PBZTransaction())->newQuery();

        if (isset($data['currency']) && $data['currency'] != GeneralConstant::ALL) {
            $query->Where('currency', $data['currency']);
        }
        if (isset($data['range_start']) && isset($data['range_end'])) {
            $query->WhereBetween('transaction_time', [$data['range_start'],$data['range_end']]);
        }

        if (isset($data['has_bill']) && $data['has_bill'] == GeneralConstant::YES){
            $query->whereHas('bill');
        }

        if (isset($data['has_bill']) && $data['has_bill'] == GeneralConstant::NO){
            $query->whereDoesntHave('bill');
        }

        $records = $query->with('bill')->orderBy('created_at', 'desc')->get();

        if ($records->count() < 1) {
            $this->customAlert('error', 'No Data Found for selected options');
            return;
        }

        $fileName = 'pbz_payments' . '_' . $data['currency'] . '.pdf';
        $title = 'pbz_pending_payments' . '_' . $data['currency'] . '.pdf';

        $parameters    = $data;
        $pdf = PDF::loadView('exports.payments.pdf.pbz-payments', compact('records', 'title', 'parameters'));
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $fileName
        );
    }

    public function excel()
    {
    }

    public function render()
    {
        return view('livewire.payments.pbz-payment-filter');
    }
}
