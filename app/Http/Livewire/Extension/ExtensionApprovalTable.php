<?php

namespace App\Http\Livewire\Extension;

use App\Enum\InstallmentRequestStatus;
use App\Models\Business;
use App\Models\Extension\ExtensionRequest;
use App\Models\Installment\InstallmentRequest;
use App\Models\WorkflowTask;
use App\Traits\WithSearch;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ExtensionApprovalTable extends DataTableComponent
{
    use CustomAlert;

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
            ->where('pinstance_type', ExtensionRequest::class)
            ->where('status', '!=', WorkflowTask::COMPLETED)
            ->where('owner', WorkflowTask::STAFF)
            ->whereHas('actors', function ($query) {
                $query->where('user_id', auth()->id());
            });
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
                    return "{$row->pinstance->extensible->total_amount} {$row->pinstance->extensible->currency}";
                }),
            Column::make('Outstanding Amount', 'extensible.outstanding_amount')
                ->label(function ($row) {
                    return "{$row->pinstance->extensible->outstanding_amount} {$row->pinstance->extensible->currency}";
                }),
            Column::make('Requested At', 'created_at')
                ->label(fn ($row) => $row->pinstance->created_at->toFormattedDateString() ?? ''),
            Column::make('Status', 'status')
                ->label(function ($row) {
                    $row = $row->pinstance;
                    return view('extension.includes.status', compact('row'));
                }),
            Column::make('Action', 'id')
                ->label(function ($row) {
                    $row = $row->pinstance;
                    return view('extension.includes.actions', compact('row'));
                }),
        ];
    }
}
