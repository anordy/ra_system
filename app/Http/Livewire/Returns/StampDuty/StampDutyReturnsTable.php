<?php

namespace App\Http\Livewire\Returns\StampDuty;

use App\Traits\WithSearch;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Traits\ReturnFilterTrait;

class StampDutyReturnsTable extends DataTableComponent
{
    use  ReturnFilterTrait;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];
    public $data         = [];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['business_location_id', 'financial_month_id']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class'   => 'table-bordered table-sm',
        ]);
    }

    public function filterData($data)
    {
        $this->data = $data;
        $this->emit('$refresh');
    }

    public function builder(): Builder
    {
        $filter = (new StampDutyReturn)->newQuery();

        $returnTable = StampDutyReturn::getTableName();

        $filter = $this->dataFilter($filter, $this->data, $returnTable);

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
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->businessLocation->name}";
                }),
            Column::make('TIN', 'business.tin')
                ->sortable()
                ->searchable(),
            Column::make(__('Total Tax'), 'total_amount_due')
                ->sortable()
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                })
                ->searchable(),
            Column::make(__('Total Tax With Penalties'), 'total_amount_due_with_penalties')
                ->sortable()
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                })
                ->searchable(),
            Column::make('Return Month', 'financialMonth.name')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->financialMonth->name} {$row->financialMonth->year->code}";
                }),
            Column::make(__('Vetting Status'), 'vetting_status')->view('returns.vetting-status'),

            Column::make(__('Application Status'), 'status')->view('returns.stamp-duty.includes.status'),

            Column::make('Action', 'id')->view('returns.stamp-duty.includes.actions'),
        ];
    }
}
