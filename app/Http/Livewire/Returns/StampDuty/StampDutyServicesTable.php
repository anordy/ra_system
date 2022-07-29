<?php

namespace App\Http\Livewire\Returns\StampDuty;

use App\Models\Returns\StampDuty\StampDutyService;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class StampDutyServicesTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return StampDutyService::latest();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        $this->setTdAttributes(function(Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->isField('description') || $column->isField('name')) {
                return [
                    'class' => 'w-25',
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make("Name", "name")
                ->sortable()
                ->searchable(),
            Column::make("Code", "code")
                ->searchable(),
            Column::make("Rate Type", "rate_type")
                ->searchable(),
            Column::make("Rate", "rate")
                ->searchable(),
            Column::make('Is Active', 'is_active')
                ->format(function ($value){
                    return $value ? 'Yes' : 'No';
                }),
            Column::make('Actions', 'id')->view('settings.returns.stamp-duty.includes.actions'),
        ];
    }
}
