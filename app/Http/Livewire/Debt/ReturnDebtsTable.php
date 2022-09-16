<?php

namespace App\Http\Livewire\Debt;

use App\Enum\ReturnCategory;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Returns\TaxReturn;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class ReturnDebtsTable extends DataTableComponent
{

    use LivewireAlert;

    public function builder(): Builder
    {
        return TaxReturn::query()
                    ->where('return_category', ReturnCategory::DEBT)
                    ->orderBy('tax_returns.created_at', 'desc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['tax_returns.business_id', 'tax_type_id', 'location_id']);
    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Branch', 'location.name')
                ->sortable()
                ->searchable(),
            Column::make('Tax Type', 'taxtype.name'),
            Column::make('Total Payable Amount', 'total_amount')
                ->format(function ($value, $row) {
                    return number_format($row->total_amount, 2);
                }),
            Column::make('Outstanding Amount', 'outstanding_amount')
                ->format(function ($value, $row) {
                    return number_format($row->outstanding_amount, 2);
                }),
            Column::make('Payment Status', 'payment_status')->view('debts.includes.status'),
            Column::make('Action', 'id')->view('debts.includes.actions'),
        ];
    }
}
