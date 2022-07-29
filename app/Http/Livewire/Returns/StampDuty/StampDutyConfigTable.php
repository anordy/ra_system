<?php

namespace App\Http\Livewire\Returns\StampDuty;

use App\Models\Returns\StampDuty\StampDutyConfig;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class StampDutyConfigTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return StampDutyConfig::latest();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        $this->setTdAttributes(function(Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->isField('Name')) {
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
            Column::make("Row Type", "row_type"),
            Column::make('Is Value Calculated', 'value_calculated')
                ->format(function ($value){
                    return $value ? 'Yes' : 'No';
                }),
            Column::make("Column Type", "col_type"),
            Column::make("Is Rate Applicable ?", "rate_applicable"),
            Column::make("Rate Type", "rate_type"),
            Column::make("Currency", "currency"),
            Column::make("Rate", "rate"),
            Column::make("Rate (USD)", "rate_usd"),
            Column::make("Formula", "formular")
                ->searchable(),
            Column::make('Is Active', 'active')
                ->format(function ($value){
                    return $value ? 'Yes' : 'No';
                }),
            Column::make('Actions', 'id')->view('settings.returns.stamp-duty.includes.actions'),
        ];
    }
}
