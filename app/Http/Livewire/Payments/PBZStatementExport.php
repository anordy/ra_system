<?php

namespace App\Http\Livewire\Payments;

use App\Exports\PBZTransactionsExport;
use App\Models\PBZStatement;
use App\Models\PBZTransaction;
use App\Traits\CustomAlert;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class PBZStatementExport extends Component
{
    use CustomAlert;

    public $statementId, $exportType;

    public function mount($statementId, $exportType)
    {
        $this->statementId = $statementId;
        $this->exportType = $exportType;
    }

    public function exportPDF()
    {
        $statement = PBZStatement::findOrFail($this->statementId);

        if ($this->exportType == PBZTransaction::class){
            $records = $statement->pbzTransactions;
            $view = 'exports.payments.pdf.statement-transactions';
            $title = "PBZ Payments {$statement->stmdt}";
            $fileName = "pbz-payments {$statement->stmdt->toDateString()}.pdf";
        } else {
            $records = $statement->pbzReversals;
            $view = 'exports.payments.pdf.statement-reversals';
            $title = "PBZ Reversals {$statement->stmdt->toDateString()}";
            $fileName = "pbz-reversals {$statement->stmdt}.pdf";
        }

        if ($records->count() < 1) {
            $this->customAlert('error', 'No data found to export.');
            return;
        }

        $pdf = PDF::loadView($view, compact('records', 'title'));
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            $fileName
        );
    }

    public function exportExcel()
    {
        $statement = PBZStatement::findOrFail($this->statementId);

        if ($this->exportType == PBZTransaction::class){
            $records = $statement->pbzTransactions;
            $title = "PBZ Payments {$statement->stmdt}";
            $fileName = "pbz-payments {$statement->stmdt->toDateString()}.xlsx";
        } else {
            $records = $statement->pbzReversals;
            $title = "PBZ Reversals {$statement->stmdt->toDateString()}";
            $fileName = "pbz-reversals {$statement->stmdt}.xlsx";
        }

        if ($records->count() < 1) {
            $this->customAlert('error', 'No data found to export.');
            return;
        }


        $this->customAlert('success', 'Exporting Excel File');
        return Excel::download(new PBZTransactionsExport($records, $title), $fileName);
    }

    public function render()
    {
        return view('livewire.payments.pbz-statement-export');
    }
}
