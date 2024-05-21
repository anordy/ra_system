<?php

namespace App\Http\Livewire\Business\Closure;

use App\Models\BusinessStatus;
use App\Models\BusinessTempClosure;
use App\Models\WorkflowTask;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ClosureApprovalProgressTable extends DataTableComponent
{
    use CustomAlert;


    protected $listeners = [
        'confirmed',
    ];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects('pinstance_type', 'user_type');
    }

    public function builder(): Builder
    {
        return WorkflowTask::with('pinstance', 'user')
            ->where('pinstance_type', BusinessTempClosure::class)
            ->where('status', '!=', WorkflowTask::COMPLETED)
            ->where('owner', WorkflowTask::STAFF);
    }

    public function columns(): array
    {
        return [
            Column::make('pinstance_id', 'pinstance_id')->hideIf(true),
            Column::make('Business', 'pinstance_type')
                ->label(fn ($row) => $row->pinstance->business->name ?? '')
                ->sortable()
                ->searchable(),
            Column::make('Branch', 'pinstance_type')
                ->label(fn ($row) => $row->pinstance->location->name ?? '')
                ->sortable()
                ->searchable(),
            Column::make('Closing Date', 'pinstance_type')
                ->label(fn ($row) => $row->pinstance->closing_date ?? '')
                ->sortable()
                ->searchable(),
            Column::make('Opening Date', 'pinstance_type')
                ->label(fn ($row) => $row->pinstance->opening_date ?? '')
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
            Column::make('Status', 'status')->view('business.closure.includes.status'),
            Column::make('Action', 'id')
                ->view('business.closure.approval-progress'),
        ];
    }
}
