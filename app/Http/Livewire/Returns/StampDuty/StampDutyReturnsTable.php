<?php

namespace App\Http\Livewire\Returns\StampDuty;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Returns\StampDuty\StampDutyReturn;

class StampDutyReturnsTable extends DataTableComponent
{
    protected $listeners = ['filterData' => 'filterData'];
    public $data         = [];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class'   => 'table-bordered table-sm',
        ]);
    }

    public function filterData($data)
    {
        $this->data = $data;
        $this->builder();
    }

    public function builder(): Builder
    {
        $data   = $this->data;
        
        $filter = (new StampDutyReturn)->newQuery();
        if (isset($data['type']) && $data['type'] != 'all') {
            $filter->Where('return_category', $data['type']);
        }
        if (isset($data['month']) && $data['month'] != 'all') {
            $filter->whereMonth('stamp_duty_returns.created_at', '=', $data['month']);
        }
        if (isset($data['year']) && $data['year'] != 'All') {
            $filter->whereYear('stamp_duty_returns.created_at', '=', $data['year']);
        }

        return $filter->with('business')->orderBy('stamp_duty_returns.created_at', 'DESC');
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
