<?php

namespace App\Http\Livewire\TaxpayerLedger;

use App\Models\TaxpayerLedger\TaxpayerLedgerPayment;
use App\Models\WithholdingAgent;
use App\Traits\CustomAlert;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ControlNumbersTable extends DataTableComponent
{
    use CustomAlert;

    public $status, $department;

    public function mount($status, $department) {
        $this->status = $status;
        $this->department = $department;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function builder(): Builder
    {
        return TaxpayerLedgerPayment::query()
            ->orderBy('taxpayer_ledger_payments.created_at', 'ASC');
    }

    public function columns(): array
    {
        return [
            Column::make('Control Number', 'status')
                ->sortable()
                ->format(function($value, $row) {
                    return $row->latestBill->control_number ?? 'N/A';
                })
                ->searchable(),
            Column::make('Debit Numbers', 'ledger_ids')
                ->sortable()
                ->searchable(),
            Column::make('Currency', 'currency')
                ->sortable()
                ->searchable(),
            Column::make('Total Amount', 'total_amount')
                ->sortable()
                ->searchable()
                ->format(function($value, $row) {
                    return number_format($value ?? 0, 2);
                }),
            Column::make('Approval Status', 'staff_id')
                ->view('taxpayer-ledger.includes.status'),
            Column::make('Bill Status', 'updated_at')
                ->view('taxpayer-ledger.includes.bill-status'),
            Column::make('Action', 'id')
                ->view('taxpayer-ledger.includes.actions')
        ];
    }


}
