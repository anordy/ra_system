<?php

namespace App\Http\Livewire\Settings\TaxRegion;

use App\Models\TaxRegion;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxRegionsTable extends DataTableComponent
{
    use CustomAlert;

    public function builder(): Builder
    {
        return  TaxRegion::query()->orderBy('created_at', 'DESC');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->isField('id')) {
                return [
                    'style' => 'width: 20%;',
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Location', 'location')
                ->sortable()
                ->searchable()
                ->format(function ($value){
                    return ucfirst($value);
                }),
            Column::make('Prefix', 'prefix')
                ->sortable()
                ->searchable(),
            Column::make('Registration Count', 'registration_count')
                ->sortable()
                ->searchable(),
        ];
    }
}
