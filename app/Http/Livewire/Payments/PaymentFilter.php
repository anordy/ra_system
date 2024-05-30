<?php

namespace App\Http\Livewire\Payments;

use App\Enum\GeneralConstant;
use App\Enum\PaymentStatus;
use App\Models\TaxType;
use App\Models\ZmBill;
use App\Traits\CustomAlert;
use Livewire\Component;
use PDF;

class PaymentFilter extends Component
{
    use CustomAlert;

    public $tableName;
    public $data;
    public $currency;
    public $tax_types;
    public $tax_type_id;
    public $range_start;
    public $range_end;
    public $status;
    public $pbz_status;

    protected $rules = [
        'range_start' => 'required|strip_tag',
        'range_end' => 'required|strip_tag'
    ];

    public function mount($tablename)
    {
        $this->tax_types = TaxType::where('category', 'main')->get();
        $this->tableName = $tablename;
        $this->range_start = date('Y-m-d', strtotime(now()));
        $this->range_end = date('Y-m-d', strtotime(now()));
    }

    public function fillter()
    {
        $this->validate();
        $filters = [
            'tax_type_id' => $this->tax_type_id,
            'currency'    => $this->currency,
            'range_start' => date('Y-m-d 00:00:00', strtotime($this->range_start)),
            'range_end'   => date('Y-m-d 23:59:59', strtotime($this->range_end)),
            'pbz_status' => $this->pbz_status
        ];
        $this->data = $filters;
        
        if ($this->tableName == 'complete-payments-table') {
            $this->status = PaymentStatus::PAID;
            $this->emitTo('App\Http\Livewire\Payments\CompletePaymentsTable', 'filterData', $filters);
        } elseif ($this->tableName == 'pending-payments-table') {
            $this->status = PaymentStatus::PENDING;
            $this->emitTo('App\Http\Livewire\Payments\PendingPaymentsTable', 'filterData', $filters);
        } elseif ($this->tableName == 'cancelled-payments-table') {
            $this->status = PaymentStatus::CANCELLED;
            $this->emitTo('App\Http\Livewire\Payments\PendingPaymentsTable', 'filterData', $filters);
        } elseif ($this->tableName == 'failed-payments-table') {
            $this->status = PaymentStatus::FAILED;
            $this->emitTo('App\Http\Livewire\Payments\FailedPaymentsTable', 'filterData', $filters);
        }
    }

    public function pdf()
    {
        $this->fillter();

        $data   = $this->data;
        $filter = (new ZmBill())->newQuery();
        $status = $this->status;

        if (isset($data['tax_type_id']) && $data['tax_type_id'] != GeneralConstant::ALL) {
            $filter->where('tax_type_id', $data['tax_type_id']);
        }
        if (isset($data['currency']) && $data['currency'] != GeneralConstant::ALL) {
            $filter->where('currency', $data['currency']);
        }
        if (isset($data['range_start']) && isset($data['range_end'])) {
            $filter->whereBetween('created_at', [$data['range_start'], $data['range_end']]);
        }

        if ($this->pbz_status == GeneralConstant::NOT_APPLICABLE){
            $filter->whereNull('pbz_status');
        }

        if ($this->pbz_status == GeneralConstant::PAID){
            $filter->where('pbz_status', GeneralConstant::PAID);
        }

        if ($this->pbz_status == GeneralConstant::REVERSED){
            $filter->where('pbz_status', GeneralConstant::REVERSED);
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
        return view('livewire.payments.payment-filter');
    }
}
