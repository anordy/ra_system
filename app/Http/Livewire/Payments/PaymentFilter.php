<?php

namespace App\Http\Livewire\Payments;

use App\Enum\PaymentStatus;
use App\Models\TaxType;
use App\Models\ZmBill;
use Livewire\Component;
use PDF;

class PaymentFilter extends Component
{
    public $tableName;
    public $data;
    public $currency;
    public $tax_types;
    public $tax_type_id;

    public function mount($tablename)
    {
        $this->tax_types = TaxType::where('category', 'main')->get();
        $this->tableName = $tablename;
    }
    
    public function fillter()
    {
        $filters = [
            'tax_type_id' => $this->tax_type_id,
            'currency'    => $this->currency,
        ];

        $this->emitTo('App\Http\Livewire\Payments\PendingPaymentsTable', 'filterData', $filters);
        $this->data = $filters;
    }

    public function pdf()
    {
        $this->fillter();

        $data   = $this->data;
        $filter = (new ZmBill())->newQuery();

        if (isset($data['tax_type_id']) && $data['tax_type_id'] != 'All') {
            $filter->Where('tax_type_id', $data['tax_type_id']);
        }
        if (isset($data['currency']) && $data['currency'] != 'All') {
            $filter->Where('currency', $data['currency']);
        }

        $records  = $filter->whereIn('status', [PaymentStatus::PENDING])->orderBy('created_at', 'DESC')->get();
        $fileName = 'pending_payments' . '_' . $data['currency'] . '.pdf';
        $title = 'pending_payments' . '_' . $data['currency'] . '.pdf';

        $parameters    = $data;

        // $pdf = PDF::loadView('exports.payments.pdf.try');
        $pdf = PDF::loadView('exports.payments.pdf.payments', compact('records', 'title', 'parameters'));
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        // view('exports.payments.pdf.try');
        return $pdf->stream($fileName);
    }

    public function excel()
    {
    }

    public function render()
    {
        return view('livewire.payments.payment-filter');
    }
}
