<?php

namespace App\Http\Livewire\TaxAgent;

use App\Models\TaxAgent;
use App\Models\TaxAgentStatus;
use App\Models\WorkflowTask;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class VerificationProgressRequestsTable extends DataTableComponent
{
    use CustomAlert;

    public function builder(): Builder
    {
        return WorkflowTask::with('pinstance', 'user')
            ->where('pinstance_type', TaxAgent::class)
            ->where('status', '!=', WorkflowTask::COMPLETED)
            ->where('owner', WorkflowTask::STAFF);
    }

    protected $listeners = ['confirmed', 'toggleStatus'];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects('pinstance_type', 'user_type');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        return [
            //            Column::make('pinstance_id', 'pinstance_id')->hideIf(true),
            Column::make('Taxpayer', 'pinstance.id')
                ->label(fn ($row) => $row->pinstance->taxpayer->first_name . ' ' . $row->pinstance->taxpayer->middle_name . ' ' . $row->pinstance->taxpayer->last_name ?? '')
                ->sortable()
                ->searchable(),
            Column::make('TIN', 'pinstance.tin_no')
                ->label(fn ($row) => $row->pinstance->tin_no)
                ->sortable()
                ->searchable(),
            Column::make('District', 'pinstance.district')
                ->label(fn ($row) => $row->pinstance->district->name ?? 'N/A')
                ->sortable()
                ->searchable(),
            Column::make('Region', 'pinstance.region')
                ->label(fn ($row) => $row->pinstance->region->name ?? '')
                ->sortable()
                ->searchable(),
            Column::make('From State', 'from_place')
                ->format(fn ($value) => str_replace('_', ' ', strtoupper($value)))
                ->sortable()
                ->searchable(),
            Column::make('Current State', 'to_place')
                ->format(fn ($value) => str_replace('_', ' ', strtoupper($value)))
                ->sortable()
                ->searchable(),
            Column::make('Status', 'status')
                ->searchable()->sortable(),
            Column::make('Action', 'pinstance_id')->view('taxagents.includes.verAction'),
        ];
    }
}
