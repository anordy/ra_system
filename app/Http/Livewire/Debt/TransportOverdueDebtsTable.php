<?php

namespace App\Http\Livewire\Debt;

use App\Enum\ReturnCategory;
use App\Models\PublicService\PublicServiceReturn;
use App\Traits\CustomAlert;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TransportOverdueDebtsTable extends DataTableComponent
{

    use CustomAlert;

    public function builder(): Builder
    {
        return PublicServiceReturn::query()
            ->where('return_category', ReturnCategory::OVERDUE)
            ->orderBy('public_service_returns.created_at', 'desc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Plate Number', 'motor.mvr.plate_number')
                ->sortable()
                ->searchable(),
            Column::make('Start Date', 'start_date')
                ->sortable()
                ->searchable(),
            Column::make('End Date', 'end_date')
                ->sortable()
                ->searchable(),
            Column::make('Payment Months', 'payment_months')
                ->sortable()
                ->searchable(),
            Column::make('Total Amount', 'amount')
                ->format(function ($value, $row) {
                    return number_format($row->amount, 2);
                }),
            Column::make('Currency', 'currency')
                ->sortable()
                ->searchable(),
            Column::make('Payment Status', 'payment_status')->view('debts.includes.status'),
            Column::make('Action', 'id')->view('debts.transports.includes.actions'),
        ];
    }
}
