<?php

namespace App\Http\Livewire\Business;

use App\Models\Business;
use App\Models\WorkflowTask;
use App\Traits\WithSearch;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\DB;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RegistrationsProgressApprovalTable extends DataTableComponent
{

    use CustomAlert;

    public function builder(): Builder
    {
        return WorkflowTask::with('pinstance', 'user')
            ->where('pinstance_type', Business::class)
            ->where('status', '!=', WorkflowTask::COMPLETED)
            ->where('owner', WorkflowTask::STAFF);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['pinstance_type', 'user_type']);
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
                ->label(fn ($row) => $row->pinstance->category->name ?? 'N/A'),
            Column::make('Business Type', 'pinstance.business_type')
                ->label(fn ($row) => strtoupper($row->pinstance->business_type ?? 'N/A')),
            Column::make('Business Name', 'pinstance.name')
                ->label(fn ($row) => $row->pinstance->name ?? 'N/A')
                ->searchable(function (Builder $query, $searchTerm) {
                    return $query->orWhereHas('pinstance', function ($query) use ($searchTerm) {
                        $query->whereRaw(DB::raw("LOWER(name) like '%' || LOWER('$searchTerm') || '%'"));
                    });
                }),
            Column::make('Taxpayer Name', 'pinstance.taxpayer_name')
                ->label(fn ($row) => $row->pinstance->taxpayer_name ?? 'N/A')
                ->searchable(function (Builder $query, $searchTerm) {
                    return $query->orWhereHas('pinstance', function ($query) use ($searchTerm) {
                       // $query->whereRaw(DB::raw("LOWER(taxpayer_name) like '%' || LOWER('$searchTerm') || '%'"));
                    });
                }),
            Column::make('TIN', 'pinstance.tin')
                ->label(fn ($row) => $row->pinstance->tin ?? ''),
            Column::make('Buss. Reg. No.', 'pinstance.reg_no')
                ->label(fn ($row) => $row->pinstance->reg_no ?? 'N/A'),
            Column::make('Mobile', 'pinstance_type')
                ->label(fn ($row) => $row->pinstance->mobile ?? ''),
            Column::make('From State', 'from_place')
                ->format(fn ($value) => strtoupper($value)),
            Column::make('Current State', 'to_place')
                ->format(fn ($value) => strtoupper($value)),
            Column::make('Status', 'pinstance.mobile')
                ->label(function ($row) {
                    return view('business.registrations.includes.approval_status', compact('row'));
                }),
            Column::make('Action', 'id')
                ->view('business.registrations.includes.approval_progress')
        ];
    }
}
