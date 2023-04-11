<?php

namespace App\Http\Livewire\Debt;

use App\Traits\WithSearch;
use Carbon\Carbon;
use App\Enum\ReturnCategory;
use App\Models\Returns\TaxReturn;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class ReturnDebtsTable extends DataTableComponent
{

    use CustomAlert, WithSearch;

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
        $this->setAdditionalSelects(['tax_returns.business_id', 'tax_type_id', 'location_id', 'financial_month_id']);
    }

    public function columns(): array
    {
        return [
            Column::make('ZIN', 'location.zin')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->location->zin}";
                }),
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Branch', 'location.name')
                ->sortable()
                ->searchable(),
            Column::make('Tax Type', 'taxtype.name'),
            Column::make('Financial Month', 'financialMonth.name')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->financialMonth->name} {$row->financialMonth->year->code}";
                }),
            Column::make('Total Payable Amount', 'total_amount')
                ->format(function ($value, $row) {
                    return number_format($row->total_amount, 2);
                }),
            Column::make('Outstanding Amount', 'outstanding_amount')
                ->format(function ($value, $row) {
                    return number_format($row->outstanding_amount, 2);
                }),
            Column::make('Days', 'filing_due_date')
                ->format(function ($value, $row) {
                    return Carbon::now()->diffInDays($row->filing_due_date);
                }),
            Column::make('Payment Status', 'payment_status')->view('debts.includes.status'),
            Column::make('Action', 'id')->view('debts.includes.actions'),
        ];
    }
}
