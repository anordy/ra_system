<?php

namespace App\Http\Livewire\Payments;

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
    public $currency = 'All';
    public $range_start;
    public $range_end;
    public $status;

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
        $filter = (new ZmBill())->newQuery();
        $status = $this->status;

        if (isset($data['tax_type_id']) && $data['tax_type_id'] != 'All') {
            $filter->Where('tax_type_id', $data['tax_type_id']);
        }
        if (isset($data['currency']) && $data['currency'] != 'All') {
            $filter->Where('currency', $data['currency']);
        }
        if (isset($data['range_start']) && isset($data['range_end'])) {
            $filter->WhereBetween('created_at', [$data['range_start'], $data['range_end']]);
        }

        $records  = $filter->with('billable')->where('status', $this->status)->orderBy('created_at', 'DESC')->get();

        if ($records->count() < 1) {
            $this->customAlert('error', 'No Data Found for selected options');
            return;
        }

        $fileName = $status. '_payments' . '_' . $data['currency'] . '.pdf';
        $title = $status. 'pending_payments' . '_' . $data['currency'] . '.pdf';

        $parameters    = $data;
        $pdf = PDF::loadView('exports.payments.pdf.payments', compact('records', 'title', 'parameters', 'status'));
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
