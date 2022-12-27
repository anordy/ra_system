<?php

namespace App\Http\Livewire\Payments;

use App\Models\BankRecon;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class BankReconTable extends DataTableComponent
{
    use LivewireAlert;

    public function builder(): Builder
    {
        return BankRecon::query()->orderBy('created_at', 'desc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        $this->setPerPageAccepted([15, 25, 50, 100]);
    }

    public function columns(): array
    {
        return [
            Column::make('Control No.', 'control_no')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return $value ? $value : 'Pending';
                }),
            Column::make('Payment Ref.', 'payment_ref')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return $value ?: 'N/A';
                }),
            Column::make('Paid Amount', 'credit_amount')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                }),
            Column::make('Transaction Type', 'transaction_type')
                ->searchable()
                ->sortable(),
            Column::make('Transaction Date', 'transaction_date')
                ->format(function ($value, $row) {
                    return $value ? $value->toFormattedDateString() : 'N/A';
                }),
            Column::make('Payer Name', 'payer_name')
                ->format(function ($value, $row) {
                    return $value ?: 'N/A';
                }),
            Column::make('Recon. Status', 'recon_status')
                ->view('payments.includes.bank-recon-status'),
            Column::make('Action', 'id')
                ->view('payments.includes.bank-recon-actions')
        ];
    }
}
