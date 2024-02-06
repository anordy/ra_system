<?php

namespace App\Http\Livewire\Business;

use App\Models\Business;
use App\Models\WorkflowTask;
use App\Traits\WithSearch;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RegistrationsApprovalTable extends DataTableComponent
{

    use CustomAlert;

    public function builder(): Builder
    {
        return WorkflowTask::with('pinstance', 'user')
            ->where('pinstance_type', Business::class)
            ->where('status', '!=', 'completed')
            ->where('owner', 'staff')
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
            Column::make('Business Category', 'pinstance.category.name')
                ->label(fn ($row) => $row->pinstance->category->name ?? 'N/A')
                ->sortable()->searchable(),
            Column::make('Business Type', 'pinstance.business_type')
                ->label(fn ($row) => strtoupper($row->pinstance->business_type ?? 'N/A'))
                ->sortable()->searchable(),
            Column::make('Business Name', 'pinstance.business.name')->label(fn ($row) => $row->pinstance->name ?? 'N/A')->sortable()->searchable(),
            Column::make('TIN', 'pinstance.tin')
                ->label(fn ($row) => $row->pinstance->tin ?? '')->sortable()->searchable(),
            Column::make('Buss. Reg. No.', 'pinstance.reg_no')
                ->label(fn ($row) => $row->pinstance->reg_no ?? 'N/A')->sortable()->searchable(),
            Column::make('Mobile', 'pinstance_type')
                ->label(fn ($row) => $row->pinstance->mobile ?? '')->sortable()->searchable(),
            Column::make('Tax Region', 'pinstance.taxRegion')
                ->label(fn ($row) => $row->pinstance->taxRegion ?? 'N/A')->sortable()->searchable(),
            Column::make('Ward', 'pinstance')
                ->label(fn ($row) => $row->pinstance->businessWardName() ?? '')->sortable()->searchable(),
            Column::make('Street', 'pinstance')
                ->label(fn ($row) =>  $row->pinstance->businessStreetName()  ?? '')->sortable()->searchable(),
            Column::make('Status', 'pinstance.mobile')
                ->label(function ($row) {
                    return view('business.registrations.includes.approval_status', compact('row'));
                }),
            Column::make('Action', 'id')
                ->view('business.registrations.includes.approval')
        ];
    }
}
