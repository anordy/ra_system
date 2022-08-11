<?php

namespace App\Http\Livewire\Settings\TaxRegion;

use App\Models\InterestRate;
use App\Models\TaxRegion;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxRegionsTable extends DataTableComponent
{
    use LivewireAlert;

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
            Column::make('Prefix', 'prefix')
                ->sortable()
                ->searchable(),
            Column::make('Registration Count', 'registration_count')
                ->sortable()
                ->searchable(),
        ];
    }
}
