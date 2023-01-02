<?php

namespace App\Http\Livewire\TaxAgent;

use App\Models\TaxAgent;
use App\Models\TaxAgentStatus;
use App\Models\WorkflowTask;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class VerificationProgressRequestsTable extends DataTableComponent
{
    use LivewireAlert;

    public function builder(): Builder
    {
        return WorkflowTask::with('pinstance', 'user')
            ->where('pinstance_type', TaxAgent::class)
            ->where('status', '!=', 'completed')
            ->where('owner', 'staff');
    }

    protected $listeners = ['confirmed', 'toggleStatus'];

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['taxpayer_id']);
    }

    public function columns(): array
    {
        return [
            Column::make('pinstance_id', 'pinstance_id')->hideIf(true),
            Column::make('Business Category', 'pinstance.category.name')
                ->label(fn($row) => $row->pinstance->category->name ?? 'N/A')
                ->sortable()
                ->searchable(),
            Column::make('Business Type', 'pinstance.business_type')
                ->label(fn($row) => strtoupper($row->pinstance->business_type ?? 'N/A'))
                ->sortable()
                ->searchable(),
            Column::make('Business Name', 'pinstance.name')
                ->label(fn($row) => $row->pinstance->name ?? 'N/A')
                ->sortable()
                ->searchable(),
            Column::make('TIN', 'pinstance.tin')
                ->label(fn($row) => $row->pinstance->tin ?? '')
                ->sortable()
                ->searchable(),
            Column::make('Buss. Reg. No.', 'pinstance.reg_no')
                ->label(fn($row) => $row->pinstance->reg_no ?? 'N/A')
                ->sortable()
                ->searchable(),
            Column::make('Mobile', 'pinstance_type')
                ->label(fn($row) => $row->pinstance->mobile ?? '')
                ->sortable()
                ->searchable(),
            Column::make('From State', 'from_place')
                ->format(fn($value) => strtoupper($value))
                ->sortable()
                ->searchable(),
            Column::make('Current State', 'to_place')
                ->format(fn($value) => strtoupper($value))
                ->sortable()
                ->searchable(),
            Column::make('Status', 'status')->view('taxagents.includes.status'),
            Column::make('Action', 'id')->view('taxagents.includes.verAction'),
        ];
    }
}
