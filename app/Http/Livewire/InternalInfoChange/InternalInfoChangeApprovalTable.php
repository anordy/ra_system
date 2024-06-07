<?php

namespace App\Http\Livewire\InternalInfoChange;

use App\Models\InternalBusinessUpdate;
use App\Models\WorkflowTask;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class InternalInfoChangeApprovalTable extends DataTableComponent
{

    use CustomAlert;

    public $model = WorkflowTask::class;

    public function builder(): Builder
    {
        return WorkflowTask::with('pinstance', 'user')
            ->where('pinstance_type', InternalBusinessUpdate::class)
            ->where('status', '!=', WorkflowTask::COMPLETED)
            ->where('owner', WorkflowTask::STAFF)
            ->whereHas('actors', function ($query) {
                $query->where('user_id', auth()->id());
            });
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
                ->label(fn ($row) => $row->pinstance->business->name ?? '')
                ->searchable(),
            Column::make("Branch", "pinstance.location_id")
                ->label(fn ($row) => $row->pinstance->location->name ?? '')
                ->searchable(),
            Column::make("Information Type", "pinstance.type")
                ->label(fn ($row) => ucfirst(str_replace('_', ' ', $row->pinstance->type))  ?? 'N/A')
                ->searchable(),
            Column::make("Triggered On", "pinstance.created_at")
                ->label(fn ($row) => $row->pinstance->created_at ?? 'N/A')
                ->searchable(),
            Column::make('Status', 'pinstance.status')
                ->label(function ($row) {
                    $status = $row->pinstance->status;
                    return view('internal-info-change.includes.status', compact('status'));
                }),
            Column::make('Actions', 'pinstance.id')
                ->label(function ($row) {
                    $id = $row->pinstance->id;
                    return view('internal-info-change.includes.approval', compact('row', 'id'));
                }),
        ];
    }
}
