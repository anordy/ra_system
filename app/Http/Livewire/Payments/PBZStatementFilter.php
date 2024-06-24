<?php

namespace App\Http\Livewire\Payments;

use App\Enum\GeneralConstant;
use App\Models\PBZStatement;
use App\Models\PBZTransaction;
use App\Models\ZmBill;
use App\Traits\CustomAlert;
use Carbon\Carbon;
use Livewire\Component;
use PDF;

class PBZStatementFilter extends Component
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
        $this->filter();
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

        if ($this->tableName == 'pbz-statements-table') {
            $this->emitTo('payments.p-b-z-statements-table', 'filterData', $filters);
        }
    }

    public function pdf()
    {
        $this->filter();

        $data   = $this->data;
        $query = (new PBZStatement())->newQuery();

        if (isset($data['currency']) && $data['currency'] != GeneralConstant::ALL) {
            $query->where('currency', $data['currency']);
        }

        if (isset($data['range_start']) && isset($data['range_end'])) {
            $query->whereBetween('stmdt', [$data['range_start'], $data['range_end']]);
        }

        $records = $query->orderBy('created_at', 'desc')->get();

        if ($records->count() < 1) {
            $this->customAlert('error', 'No Data Found for selected options');
            return;
        }

        $fileName = 'pbz_statements' . '_' . $data['currency'] . '.pdf';
        $title = 'pbz_statements' . '_' . $data['currency'] . '.pdf';

        $parameters    = $data;
        $pdf = PDF::loadView('exports.payments.pdf.pbz-statements', compact('records', 'title', 'parameters'));
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
        return view('livewire.payments.pbz-statement-filter');
    }
}
