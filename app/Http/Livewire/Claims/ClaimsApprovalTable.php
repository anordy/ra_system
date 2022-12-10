<?php

namespace App\Http\Livewire\Claims;

use App\Enum\TaxClaimStatus;
use App\Models\Claims\TaxClaim;
use App\Models\WorkflowTask;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ClaimsApprovalTable extends DataTableComponent
{
    public $pending;
    public $rejected;

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
            ->where('pinstance_type', TaxClaim::class)
            ->where('status', '!=', 'completed')
            ->where('owner', 'staff')
            ->whereHas('operators', function($query){
                $query->where('user_id', auth()->id());
            })
            ->with('pinstance')->orderByDesc('id');
    }

    public function columns(): array
    {
        return [
            Column::make('pinstance_id', 'pinstance_id')->hideIf(true),
            Column::make('Business Name', 'pinstance.business.name')
                ->label(fn ($row) => $row->pinstance->business->name ?? ''),
            Column::make('Claimed Amount', 'pinstance.amount')
                ->label(function ($row){
                    $formattedAmount = number_format($row->pinstance->amount, 2);
                    return "{$row->pinstance->currency}. {$formattedAmount}";
                }),
            Column::make('Tax Type', 'pinstance.taxType.name')
                ->label(fn ($row) => $row->pinstance->taxType->name ?? ''),
            Column::make('Mobile', 'pinstance.mobile')
                ->label(fn ($row) => $row->pinstance->business->mobile ?? 'N/A'),

            Column::make('Status', 'pinstance.mobile')
                ->label(function ($row){
                    return view('claims.includes.status', ['row' => $row->pinstance]);
                }),
            Column::make('Action', 'id')
                ->label(function ($row){
                    return view('claims.includes.actions', ['value' => $row->pinstance->id]);
                }),
        ];
    }
}
