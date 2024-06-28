<?php

namespace App\Http\Livewire\Tra;

use App\Models\Tra\ChassisNumber;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ChassisNumbersTable extends DataTableComponent
{
    use CustomAlert;

    protected $model = ChassisNumber::class;
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
            Column::make('Chassis Number', 'chassis_number')
                ->sortable()
                ->searchable(),
            Column::make('TANSAD Number', 'tansad_number')
                ->sortable()
                ->searchable(),
            Column::make('Importer Name', 'importer_name')
                ->sortable()
                ->searchable(),
            Column::make('Importer TIN', 'importer_tin')
                ->sortable()
                ->searchable(),
            Column::make('Make', 'make')
                ->sortable()
                ->searchable(),
            Column::make('Model Type', 'model_type')
                ->sortable()
                ->searchable(),
            Column::make('Body Type', 'body_type')
                ->sortable()
                ->searchable(),
            Column::make('Color', 'color')
                ->sortable()
                ->searchable(),
            Column::make('Engine Number', 'engine_number')
                ->sortable()
                ->searchable(),
            Column::make('Engine CC', 'engine_cubic_capacity')
                ->sortable()
                ->searchable(),
            Column::make('Plate Number', 'plate_number')
                ->sortable()
                ->searchable(),
            Column::make('Reg Sync', 'tra_registration_synced')
                ->format(function ($value, $row) {
                    if ($value == 0) {
                        return <<<HTML
                            <span class="badge badge-warning p-2 rounded-0" >Not Synced</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<<HTML
                            <span class="badge badge-success p-2 rounded-0" >Synced</span>
                        HTML;
                    }
                })
                ->html(),
            Column::make('De-Reg Sync', 'tra_deregistration_synced')
                ->format(function ($value, $row) {
                    if ($value == 0) {
                        return <<<HTML
                            <span class="badge badge-warning p-2 rounded-0" >Not Synced</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<<HTML
                            <span class="badge badge-success p-2 rounded-0" >Synced</span>
                        HTML;
                    }
                })
                ->html(),
            Column::make('Status', 'status')
                ->format(function ($value, $row) {
                    if ($value == 1) {
                        return <<<HTML
                            <span class="badge badge-info p-2 rounded-0">Registration</span>
                        HTML;
                    } elseif ($value == 2) {
                        return <<<HTML
                            <span class="badge badge-warning p-2 rounded-0" >Deregistration</span>
                        HTML;
                    }
                })
                ->html(),
            Column::make('Action', 'id')
                ->view('tra.includes.chassis-actions')
        ];
    }

}
