<?php

namespace App\Http\Livewire\Investigation;

use App\Enum\TaxInvestigationStatus;
use App\Models\Investigation\TaxInvestigation;
use App\Models\WorkflowTask;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxInvestigationApprovalProgressTable extends DataTableComponent
{

    use CustomAlert;

    public $model = WorkflowTask::class;

    public function builder(): Builder
    {
        return WorkflowTask::with('pinstance', 'user')
            ->where('pinstance_type', TaxInvestigation::class)
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

        $this->setThAttributes(function (Column $column) {
            if ($column->getTitle() == 'Tax Types') {
                return [
                    'style' => 'width: 20%;',
                ];
            }
            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make('user_type', 'user_id')->hideIf(true),
            Column::make('ZTN No')
                ->label(fn ($row) => $row->pinstance->business->ztn_number ?? ''),
            Column::make('TIN')
                ->label(fn ($row) => $row->pinstance->business->tin ?? ''),
            Column::make('Business Name')
                ->label(fn ($row) => $row->pinstance->business->name ?? ''),
            Column::make('Business Location')
                ->label(fn ($row) => $row->pinstance->taxInvestigationLocationNames()),
            Column::make('Tax Types')
                ->label(fn ($row) => $row->pinstance->taxInvestigationTaxTypeNames()),
            Column::make('Period From', 'pinstance.period_from')
                ->label(fn ($row) => $row->pinstance->period_from ?? ''),
            Column::make('Period To', 'pinstance.period_to')
                ->label(fn ($row) => $row->pinstance->period_to ?? ''),
            Column::make('Filled By', 'pinstance.created_by_id')
                ->label(function ($row) {
                    $user = $row->pinstance->createdBy;
                    return $user->full_name ?? '';
                }),
            Column::make('Filled On', 'created_at')
                ->format(fn ($value) => Carbon::create($value)->format('d-m-Y')),
            Column::make('From State', 'from_place')
                ->format(fn ($value) => strtoupper($value))
                ->sortable()->searchable(),
            Column::make('Current State', 'to_place')
                ->format(fn ($value) => strtoupper($value))
                ->sortable()->searchable(),
            Column::make('Action', 'pinstance_id')
                ->view('investigation.approval.approval-progress-action')
                ->html(true),
        ];
    }
}
