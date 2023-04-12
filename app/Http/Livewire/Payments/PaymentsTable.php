<?php

namespace App\Http\Livewire\Payments;

use App\Enum\PaymentStatus;
use App\Models\ZmBill;
use App\Traits\WithSearch;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PaymentsTable extends DataTableComponent
{
    use CustomAlert, WithSearch;

    public function builder(): Builder
    {
        return ZmBill::whereHas('bill_payments')->where('status', PaymentStatus::PAID)->orderBy('created_at', 'DESC');
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
                    return $value ? $value : 'Pending';
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
            Column::make('Actions', 'id')
                ->view('payments.includes.actions')
        ];
    }
}
