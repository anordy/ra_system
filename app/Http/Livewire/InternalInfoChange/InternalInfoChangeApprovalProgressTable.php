<?php

namespace App\Http\Livewire\InternalInfoChange;

use App\Models\InternalBusinessUpdate;
use App\Models\WorkflowTask;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\DB;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class InternalInfoChangeApprovalProgressTable extends DataTableComponent
{

    use CustomAlert;

    public $model = WorkflowTask::class;

    public function builder(): Builder
    {
        return WorkflowTask::with('pinstance', 'user')
            ->where('pinstance_type', InternalBusinessUpdate::class)
            ->where('status', '!=', WorkflowTask::COMPLETED)
            ->where('owner', WorkflowTask::STAFF);
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
            Column::make("Branch", "pinstance.location_id")
                ->label(fn ($row) => $row->pinstance->location->name ?? ''),
            Column::make("Information Type", "pinstance.type")
                ->label(fn ($row) => ucfirst(str_replace('_', ' ', $row->pinstance->type))  ?? 'N/A'),
            Column::make("Triggered On", "pinstance.created_at")
                ->label(fn ($row) => $row->pinstance->created_at ?? 'N/A'),
            Column::make('From State', 'from_place')
                ->format(fn ($value) => strtoupper($value)),
            Column::make('Current State', 'to_place')
                ->format(fn ($value) => strtoupper($value)),
            Column::make('Status', 'pinstance.status')
                ->label(function ($row) {
                    $status = $row->pinstance->status;
                    return view('internal-info-change.includes.status', compact('status'));
                }),
            Column::make('Actions', 'pinstance.id')
                ->label(function ($row) {
                    $id = $row->pinstance->id;
                    return view('internal-info-change.includes.action', compact('row', 'id'));
                }),
        ];
    }
}
