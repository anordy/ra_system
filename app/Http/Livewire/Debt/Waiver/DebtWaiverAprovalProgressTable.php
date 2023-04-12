<?php

namespace App\Http\Livewire\Debt\Waiver;

use App\Models\Debts\DebtWaiver;
use App\Models\WaiverStatus;
use App\Models\WorkflowTask;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class DebtWaiverAprovalProgressTable extends DataTableComponent
{
    use CustomAlert;

    public function mount()
    {
    }
    public function builder(): Builder
    {
        return WorkflowTask::with('pinstance', 'user')
            ->where('pinstance_type', DebtWaiver::class)
            ->where('status', '!=', 'completed')
            ->where('owner', 'staff');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects('pinstance_type', 'user_type');
    }

    public function columns(): array
    {

        return [
            Column::make('pinstance_id', 'pinstance_id')->hideIf(true),
            Column::make('Business', 'pinstance_type')
                ->label(fn ($row) => $row->pinstance->debt->business->name ?? '')
                ->sortable()
                ->searchable(),
            Column::make('Branch', 'pinstance_type')
                ->label(fn ($row) => $row->pinstance->debt->location->name ?? '')
                ->sortable()
                ->searchable(),
            Column::make('Tax Type', 'pinstance_type')
                ->label(fn ($row) => $row->pinstance->debt->taxType->name ?? '')
                ->sortable()
                ->searchable(),
            Column::make('Category', 'pinstance_type')
                ->label(fn ($row) => $row->pinstance->category ?? '')
                ->sortable()
                ->searchable(),
            Column::make('Request Date', 'pinstance_type')
                ->label(fn ($row) => $row->pinstance->created_at ?? '')
                ->sortable()
                ->searchable(),
            Column::make('From State', 'from_place')
                ->format(fn ($value) => strtoupper($value))
                ->sortable()
                ->searchable(),
            Column::make('Current State', 'to_place')
                ->format(fn ($value) => strtoupper($value))
                ->sortable()
                ->searchable(),
            Column::make('Status', 'status')->view('debts.waivers.includes.status'),
            Column::make('Action', 'id')->view('debts.waivers.includes.approval-progress'),
        ];
    }
}
