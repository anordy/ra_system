<?php

namespace App\Http\Livewire\Payments;

use App\Enum\PaymentStatus;
use App\Models\ZmBill;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class CancelledPaymentsTable extends DataTableComponent
{
    use LivewireAlert;

    public function builder(): Builder
    {
        return ZmBill::whereIn('status', [PaymentStatus::CANCELLED])->orderBy('created_at', 'DESC');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects('tax_type_id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        $this->setPerPageAccepted([15, 25, 50, 100]);
    }

    public function columns(): array
    {
        return [
            Column::make('Control No.', 'control_number')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return $value ? $value : 'N/A';
                }),
            Column::make('Bill Amount', 'amount')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                }),
            Column::make('Currency', 'currency')
                ->sortable()
                ->searchable(),
            Column::make('Tax Type', 'tax_type_id')
                ->label(fn ($row) => $row->taxType->name ?? 'N/A')
                ->sortable()
                ->searchable(),
            Column::make('Payer Name', 'payer_name'),
            Column::make('Payer Email', 'payer_email'),
            Column::make('Description', 'description'),
            Column::make('Status', 'status'),
            Column::make('Actions', 'id')
                ->view('payments.includes.actions')
        ];
    }
}
