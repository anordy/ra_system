<?php

namespace App\Http\Livewire\Installment;

use App\Models\Installment\InstallmentRequest;
use App\Models\WorkflowTask;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class InstallmentRequestsApprovalProgressTable extends DataTableComponent
{
    use LivewireAlert;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects('pinstance_type', 'user_type');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects('pinstance_type', 'user_type');
    }

    public function builder(): Builder
    {
        return WorkflowTask::with('pinstance', 'user')
            ->where('pinstance_type', InstallmentRequest::class)
            ->where('status', '!=', 'completed')
            ->where('owner', 'staff');
    }

    public function columns(): array
    {
        return [
            Column::make('pinstance_id', 'pinstance_id')->hideIf(true),
            Column::make('ZIN', 'pinstance.location.zin')
                ->label(fn ($row) => $row->pinstance->location->zin ?? ''),
            Column::make('Business Name', 'pinstance.business.name')
                ->label(fn ($row) => $row->pinstance->business->name ?? ''),
            Column::make('Branch Name', 'pinstance.location.name')
                ->label(fn ($row) => $row->pinstance->location->name ?? ''),
            Column::make('Type', 'pinstance.taxType.name')
                ->label(fn ($row) => $row->pinstance->taxType->name ?? ''),
            Column::make('Total Amount', 'pinstance.extensible.total_amount')
                ->label(function ($row) {
                    return "{$row->pinstance->installable->total_amount} {$row->pinstance->installable->currency}";
                }),
            Column::make('Outstanding Amount', 'installable.outstanding_amount')
                ->label(function ($row) {
                    return "{$row->pinstance->installable->outstanding_amount} {$row->pinstance->installable->currency}";
                }),
            Column::make('Requested At', 'created_at')
                ->label(fn ($row) => $row->pinstance->created_at->toFormattedDateString() ?? ''),
            Column::make('From State', 'from_place')
                ->format(fn ($value) => strtoupper($value))
                ->sortable()->searchable(),
            Column::make('Current State', 'to_place')
                ->format(fn ($value) => strtoupper($value))
                ->sortable()->searchable(),
            Column::make('Status', 'status')
                ->label(function ($row) {
                    $row = $row->pinstance;
                    return view('installment.requests.includes.status', compact('row'));
                }),
            Column::make('Action', 'id')
                ->label(function ($row) {
                    $row = $row->pinstance;
                    return view('installment.requests.includes.approval-progress-action', compact('row'));
                }),
        ];
    }
}
