<?php

namespace App\Http\Livewire\Payments;

use App\Enum\GeneralConstant;
use App\Exports\PBZTransactionsExport;
use App\Models\PBZReversal;
use App\Models\PBZTransaction;
use App\Traits\CustomAlert;
use Carbon\Carbon;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
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
    public $zanmalipo_status = GeneralConstant::ALL;

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
            'currency' => $this->currency,
            'range_start' => date('Y-m-d 00:00:00', strtotime($this->range_start)),
            'range_end' => date('Y-m-d 23:59:59', strtotime($this->range_end)),
            'has_bill' => $this->has_bill,
            'zanmalipo_status' => $this->zanmalipo_status
        ];

        $this->data = $filters;

        if ($this->tableName == 'p-b-z-reversals-table') {
            $this->emitTo('payments.p-b-z-reversals-table', 'filterData', $filters);
        } elseif ($this->tableName == 'p-b-z-payments-table') {
            $this->emitTo('payments.p-b-z-payments-table', 'filterData', $filters);
        }
    }

    public function exportPDF()
    {
        $this->filter();

        $data = $this->data;

        if ($this->tableName == 'p-b-z-reversals-table') {
            $query = (new PBZReversal())->newQuery();
        } elseif ($this->tableName == 'p-b-z-payments-table') {
            $query = (new PBZTransaction())->newQuery();
        }

        if (isset($data['currency']) && $data['currency'] != GeneralConstant::ALL) {
            $query->Where('currency', $data['currency']);
        }
        if (isset($data['range_start']) && isset($data['range_end'])) {
            $query->WhereBetween('transaction_time', [$data['range_start'], $data['range_end']]);
        }

        if (isset($data['has_bill']) && $data['has_bill'] == GeneralConstant::YES) {
            $query->whereHas('bill');
        }

        if (isset($data['has_bill']) && $data['has_bill'] == GeneralConstant::NO) {
            $query->whereDoesntHave('bill');
        }

        if (isset($data['zanmalipo_status']) && $data['zanmalipo_status'] != GeneralConstant::ALL) {
            $query->whereHas('bill', function ($query) use ($data){
                $query->where('status', $data['zanmalipo_status']);
            });
        }

        $records = $query->with('bill')->orderBy('created_at', 'desc')->get();

        if ($records->count() < 1) {
            $this->customAlert('error', 'No Data Found for selected options');
            return;
        }

        $fileName = 'pbz-transactions-' . $data['currency'] . '.pdf';
        $title = 'PBZ Transactions ' . $data['currency'] . '.pdf';

        $parameters = $data;
        $pdf = PDF::loadView('exports.payments.pdf.pbz-payments', compact('records', 'title', 'parameters'));
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            $fileName
        );
    }

    public function exportExcel()
    {
        $this->filter();

        $data = $this->data;
        if ($this->tableName == 'p-b-z-reversals-table') {
            $query = (new PBZReversal())->newQuery();
        } elseif ($this->tableName == 'p-b-z-payments-table') {
            $query = (new PBZTransaction())->newQuery();
        }

        if (isset($data['currency']) && $data['currency'] != GeneralConstant::ALL) {
            $query->Where('currency', $data['currency']);
        }
        if (isset($data['range_start']) && isset($data['range_end'])) {
            $query->WhereBetween('transaction_time', [$data['range_start'], $data['range_end']]);
        }

        if (isset($data['has_bill']) && $data['has_bill'] == GeneralConstant::YES) {
            $query->whereHas('bill');
        }

        if (isset($data['has_bill']) && $data['has_bill'] == GeneralConstant::NO) {
            $query->whereDoesntHave('bill');
        }

        if (isset($data['zanmalipo_status']) && $data['zanmalipo_status'] != GeneralConstant::ALL) {
            $query->whereHas('bill', function ($query) use ($data){
                $query->where('status', $data['zanmalipo_status']);
            });
        }

        $records = $query->with('bill')->orderBy('created_at', 'desc')->get();

        if ($records->count() < 1) {
            $this->customAlert('error', 'No Data Found for selected options');
            return;
        }

        $fileName = 'pbz-transactions-' . time() . '.xlsx';
        $title = 'PBZ Transactions Between ' . $data['range_start'] . ' and ' . $data['range_end'];

        $this->customAlert('success', 'Exporting Excel File');
        return Excel::download(new PBZTransactionsExport($records, $title), $fileName);
    }

    public function render()
    {
        return view('livewire.payments.pbz-payment-filter');
    }
}
