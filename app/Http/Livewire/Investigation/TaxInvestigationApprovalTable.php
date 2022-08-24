<?php

namespace App\Http\Livewire\Investigation;

use App\Enum\TaxInvestigationStatus;
use App\Models\Investigation\TaxInvestigation;
use App\Models\WorkflowTask;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxInvestigationApprovalTable extends DataTableComponent
{

    use LivewireAlert;

    public $model = TaxInvestigation::class;

    public function builder(): Builder
    {
        return WorkflowTask::with('pinstance', 'user')
            ->where('pinstance_type', TaxInvestigation::class)
            ->where('status', 'running')
            ->whereJsonContains('operators', auth()->user()->id)
            ->orderBy('created_at', 'DESC');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['user_type', 'pinstance_type']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('pinstance_id')->hideIf(true),
            Column::make('ZRB No', 'pinstance_id')
                ->label(fn ($row) => $row->pinstance->location->zin ?? ''),
            Column::make('TIN', 'pinstance_id')
                ->label(fn ($row) => $row->pinstance->business->tin ?? ''),
            Column::make('Business Name', 'pinstance_id')
                ->label(fn ($row) => $row->pinstance->business->name ?? ''),
            Column::make('Business Location', 'pinstance_id')
                ->label(fn ($row) => $row->pinstance->location->name ?? ''),
            Column::make('TaxType', 'pinstance_id')
                ->label(fn ($row) => $row->pinstance->taxType->name ?? ''),
            Column::make('Period From', 'pinstance_id')
                ->label(fn ($row) => $row->pinstance->period_from ?? ''),
            Column::make('Period To', 'pinstance_id')
                ->label(fn ($row) => $row->pinstance->period_to ?? ''),
            Column::make('Created By', 'pinstance_id')
                ->label(fn ($row) => $row->pinstance->createdBy->full_name ?? ''),
            Column::make('Created On', 'created_at')
                ->label(fn ($row) => Carbon::create($row->pinstance->created_at)->toDayDateTimeString()),
            Column::make('Action', 'id')
                ->view('investigation.approval.action')
                ->html(true),

        ];
    }
}
