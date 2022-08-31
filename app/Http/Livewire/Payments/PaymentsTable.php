<?php

namespace App\Http\Livewire\Payments;

use App\Enum\PaymentStatus;
use App\Models\ZmBill;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PaymentsTable extends DataTableComponent
{
    use LivewireAlert;

    public function builder(): Builder
    {
        return ZmBill::whereHas('bill_payments')->where('status', PaymentStatus::PAID)->orderBy('created_at', 'DESC');
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
            Column::make('Control No.', 'control_number')
                ->sortable()
                ->searchable(),
            Column::make('Bill Amount', 'amount')
                ->sortable()
                ->searchable(),
            Column::make('Currency', 'currency')
                ->sortable()
                ->searchable(),
            Column::make('Payer Name', 'payer_name'),
            Column::make('Payer Email', 'payer_email'),
            Column::make('Description', 'description'),
        ];
    }

}
