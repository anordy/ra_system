<?php

namespace App\Http\Livewire\Business\Files;

use App\Models\BranchStatus;
use App\Models\BusinessFileType;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\BusinessLocation;

class FileTypesTable extends DataTableComponent
{
    protected $model = BusinessLocation::class;

    public function builder(): Builder
    {
        return BusinessFileType::latest();
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
            Column::make("Description", "description")
                ->searchable(),
            Column::make("Business Category", "business_type")
                ->searchable(),
            Column::make('Is required ?', 'is_required')
                ->format(function ($value){
                    return $value ? 'Yes' : 'No';
                }),

            Column::make('Approval Status', 'is_approved')
                ->format(function ($value, $row) {
                    if ($value == 0) {
                        return <<< HTML
                            <span style="border-radius: 0 !important;" class="badge badge-warning p-2" >Not Approved</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<< HTML
                            <span style="border-radius: 0 !important;" class="badge badge-success p-2" >Approved</span>
                        HTML;
                    }
                    elseif ($value == 2) {
                        return <<< HTML
                            <span style="border-radius: 0 !important;" class="badge badge-danger p-2" >Rejected</span>
                        HTML;
                    }

                })->html(),
            Column::make('Edit Status', 'is_updated')
                ->format(function ($value, $row) {
                    if ($value == 0) {
                        return <<<HTML
                            <span style="border-radius: 0 !important;" class="badge badge-warning p-2" >Not Updated</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<<HTML
                            <span style="border-radius: 0 !important;" class="badge badge-success p-2" >Updated</span>
                        HTML;
                    }
                })
                ->html(),
            Column::make('Actions', 'id')->view('business.files.includes.actions'),
        ];
    }
}
