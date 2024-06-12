<?php

namespace App\Http\Livewire\Assesments\Waiver;

use App\Models\Disputes\Dispute;
use App\Models\WorkflowTask;
use App\Traits\WithSearch;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class WaiverApprovalProgressTable extends DataTableComponent
{
    use CustomAlert;
    public $category;

    public function mount($category)
    {
        $this->category = $category;
    }

    public function builder(): Builder
    {
        $workflow = WorkflowTask::with('pinstance', 'user')
            ->where('pinstance_type', Dispute::class)
            ->where('status', '!=', WorkflowTask::COMPLETED)
            ->whereHasMorph('pinstance', Dispute::class, function ($query) {
                $query->where('category', $this->category);
            })
            ->where('owner', WorkflowTask::STAFF)
            ->whereHas('actors', function ($query) {
                $query->where('user_id', auth()->id());
            });
        return $workflow;
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
            Column::make('TIN', 'pinstance.business.tin')
                ->label(fn ($row) => $row->pinstance->business->tin ?? ''),
            Column::make('Business Name', 'pinstance.business.name')
                ->label(fn ($row) => $row->pinstance->business->name ?? ''),
            Column::make('Owner ', 'pinstance.business.owner_designation')
                ->label(fn ($row) => $row->pinstance->business->owner_designation ?? ''),
            Column::make('Business Mobile', 'pinstance.business.mobile')
                ->label(fn ($row) => $row->pinstance->business->mobile ?? ''),
            Column::make('Category', 'pinstance.category')
                ->label(fn ($row) => $row->pinstance->category ?? ''),
            Column::make('Filled On', 'created_at')
                ->format(fn ($value) => Carbon::create($value)->toDayDateTimeString()),
            Column::make('From State', 'from_place')
                ->format(fn ($value) => strtoupper($value))
                ->sortable()->searchable(),
            Column::make('Current State', 'to_place')
                ->format(fn ($value) => strtoupper($value))
                ->sortable()->searchable(),
            Column::make('Action', 'pinstance_id')
                ->view('assesments.waiver.includes.approval_progress_action')
                ->html(true),
        ];
    }
}
