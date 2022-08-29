<?php

namespace App\Http\Livewire\Returns\StampDuty;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Returns\StampDuty\StampDutyReturn;

class StampDutyReturnsTable extends DataTableComponent
{

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
        return StampDutyReturn::select('financial_month_id')
            ->with('business')
            ->orderBy('stamp_duty_returns.created_at', 'DESC');
    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Branch / Location', 'businessLocation.name')
                ->sortable()
                ->searchable(),
            Column::make('TIN', 'business.tin')
                ->sortable()
                ->searchable(),
            Column::make('Total Tax', 'total_amount_due')
                ->sortable()
                ->searchable(),
            Column::make('Financial Year', 'financialYear.name')
                ->sortable()
                ->searchable(),
            Column::make('Financial Month', 'financialMonth.name')
                ->sortable()
                ->searchable(),
            Column::make('Action', 'id')->view('returns.stamp-duty.includes.actions'),
        ];
    }
}
