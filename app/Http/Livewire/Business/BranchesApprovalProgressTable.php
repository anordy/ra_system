<?php

namespace App\Http\Livewire\Business;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\BusinessLocation;
use App\Models\WorkflowTask;

class BranchesApprovalProgressTable extends DataTableComponent
{

    public function builder(): Builder
    {
        return WorkflowTask::with('pinstance', 'user')
            ->where('pinstance_type', BusinessLocation::class)
            ->where('status', '!=', 'completed')
            ->where('owner', 'staff');
    }

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
            Column::make('pinstance_id', 'pinstance_id')->hideIf(true),
            Column::make("Business Name", "pinstance.business_id")
                ->label(fn ($row) => $row->pinstance->business->name ?? ''),
            Column::make("Business Type", "pinstance.business.business_type")
                ->label(fn ($row) => strtoupper($row->pinstance->business->business_type ?? '')),
            Column::make("Branch Name", "pinstance.is_headquarter")
                ->label(fn ($row) =>  $row->is_headquarter === 1 ? "Head Quarters" : ($row->pinstance->name ?? '')),
            Column::make("Region", "pinstance.region.name")
                ->label(fn ($row) => $row->pinstance->region->name ?? '')
                ->searchable(),
            Column::make("District", "pinstance.district.name")
                ->label(fn ($row) => $row->pinstance->district->name ?? '')
                ->searchable(),
            Column::make("Street", "pinstance.street")
                ->label(fn ($row) => $row->pinstance->street ?? '')
                ->searchable(),
            Column::make('From State', 'from_place')
                ->format(fn ($value) => strtoupper($value))
                ->sortable()->searchable(),
            Column::make('Current State', 'to_place')
                ->format(fn ($value) => strtoupper($value))
                ->sortable()->searchable(),

        ];
    }
}
