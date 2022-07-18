<?php

namespace App\Http\Livewire\Workflow;

use App\Models\Workflow;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class WorkflowConfigTable extends DataTableComponent
{
    use LivewireAlert;

    public function builder(): Builder
    {
        return Workflow::query()->orderBy('created_at', 'DESC');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['transitions', 'places']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }



    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()
                ->searchable()
                ->format(fn ($value) => strtoupper(str_replace('_', ' ', $value))),
            Column::make('type', 'type')
                ->sortable()
                ->searchable(),
            Column::make('Initial Place', 'initial_marking'),
            Column::make('Supports', 'supports'),
            Column::make('Places', 'places')
                ->label(function ($row) {
                    $data = collect(json_decode($row->places, true));
                    return $data->map(function ($item, $key) {
                        return '<span class="badge badge-info p-1 font-weight-light">' . $key . '</span>';
                    })->implode(' ');
                })->html(),
            Column::make('Transitions', 'transitions')
                ->label(function ($row) {
                    $data = collect(json_decode($row->transitions, true));
                    return $data->map(function ($item, $key) {
                        return '<span class="badge badge-info p-1 font-weight-light">' . $key . '</span>';
                    })->implode(' ');
                })->html(),
            Column::make('Action', 'id')
                ->format(function ($value) {
                    $url = route('system.workflow.show', encrypt($value));
                    return <<< HTML
                            <a class="btn btn-success rounded btn-sm" href="{$url}" ><i class="bi bi-gear-wide-connected"></i>Configure </a>
                        HTML;
                })->html()
        ];
    }
}
