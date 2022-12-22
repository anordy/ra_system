<?php

namespace App\Http\Livewire\Business;

use App\Models\Business;
use App\Models\WorkflowTask;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RegistrationsProgressApprovalTable extends DataTableComponent
{

    use LivewireAlert;

    public function builder(): Builder
    {
        return WorkflowTask::with('pinstance', 'user')
            ->where('pinstance_type', Business::class)
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
            Column::make('pinstance_id', 'pinstance_id')->hideIf(true),
            Column::make('Business Name', 'pinstance.business.name')->label(fn ($row) => $row->pinstance->name ?? 'N/A')->sortable()->searchable(),
            Column::make('TIN', 'pinstance.tin')
                ->label(fn ($row) => $row->pinstance->tin ?? '')->sortable()->searchable(),
            Column::make('Buss. Reg. No.', 'pinstance.reg_no')
                ->label(fn ($row) => $row->pinstance->reg_no ?? 'N/A')->sortable()->searchable(),
            Column::make('Mobile', 'pinstance_type')
                ->label(fn ($row) => $row->pinstance->mobile ?? '')->sortable()->searchable(),
            Column::make('Status', 'pinstance.mobile')
                ->label(function ($row){
                    return view('business.registrations.includes.approval_status', compact('row'));
                }),
            Column::make('Action', 'id')
                ->view('business.registrations.includes.approval')
        ];
    }
}
