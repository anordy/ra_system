<?php

namespace App\Http\Livewire\Business\Deregister;

use App\Models\BusinessDeregistration;
use App\Models\BusinessStatus;
use App\Models\WorkflowTask;
use App\Traits\WithSearch;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class ProgressDeregisterBusinessTable extends DataTableComponent
{
    use WithSearch;

    public function builder(): Builder
    {
        return WorkflowTask::with('pinstance', 'user')
            ->where('pinstance_type', BusinessDeregistration::class)
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
            Column::make('ZTN No', 'pinstance.business.ztn_number')
                ->label(fn ($row) => $row->pinstance->business->ztn_number ?? '')
                ->sortable(),
            Column::make('Name', 'pinstance.business.name')
                ->label(fn ($row) => $row->pinstance->business->name ?? '')
                ->sortable(),
            Column::make('Deregistration Type', 'pinstance.deregistration_type')
                ->sortable()
                ->label(function ($row) {
                    if ($row->pinstance->deregistration_type == 'all') {
                        return 'All Locations';
                    } else {
                        return 'Single Location';
                    }
                }),
            Column::make('Location', 'pinstance.location.name')
                ->label(fn ($row) => $row->pinstance->business->name ?? '')
                ->sortable(),
            Column::make('Date of De-registration', 'pinstance.deregistration_date')
                ->label(function ($row) {
                    return Carbon::create($row->pinstance->deregistration_date)->toFormattedDateString();
                })
                ->sortable(),
            Column::make('From State', 'from_place')
                ->format(fn ($value) => strtoupper($value))
                ->sortable()->searchable(),
            Column::make('Current State', 'to_place')
                ->format(fn ($value) => strtoupper($value))
                ->sortable()->searchable(),
            Column::make('Action', 'pinstance_id')
                ->view('business.deregister.approval-action'),
        ];
    }
}
