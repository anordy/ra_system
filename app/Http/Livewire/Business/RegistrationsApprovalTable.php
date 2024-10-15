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

class RegistrationsApprovalTable extends DataTableComponent
{

    use CustomAlert;

    public function builder(): Builder
    {
        return WorkflowTask::with('pinstance', 'user')
            ->where('pinstance_type', Business::class)
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
            Column::make('Business Category', 'pinstance.category.name')
                ->label(fn ($row) => $row->pinstance->category->name ?? 'N/A')
                ->sortable(),
            Column::make('Business Type', 'pinstance.business_type')
                ->label(fn ($row) => strtoupper($row->pinstance->business_type ?? 'N/A'))
                ->sortable(),
            Column::make('Business Name', 'pinstance.name')
                ->label(fn ($row) => $row->pinstance->name ?? 'N/A')
                ->sortable()
                ->searchable(function (Builder $query, $searchTerm) {
                    return $query->orWhereHas('pinstance', function ($query) use ($searchTerm) {
                        $query->whereRaw(DB::raw("LOWER(name) like '%' || LOWER('$searchTerm') || '%'"));
                    });
                }),
            Column::make('TIN', 'pinstance.tin')
                ->label(fn ($row) => $row->pinstance->tin ?? '')->sortable(),
            Column::make('Buss. Reg. No.', 'pinstance.reg_no')
                ->label(fn ($row) => $row->pinstance->reg_no ?? 'N/A')->sortable(),
            Column::make('Mobile', 'pinstance_type')
                ->label(fn ($row) => $row->pinstance->mobile ?? '')->sortable(),
            Column::make('Tax Region', 'pinstance.taxRegion')
                ->label(fn ($row) => $row->pinstance->headquarter->taxRegion->name ?? 'N/A')->sortable(),
            Column::make('Ward', 'pinstance')
                ->label(fn ($row) => $row->pinstance->businessWardName() ?? '')->sortable(),
            Column::make('Street', 'pinstance')
                ->label(fn ($row) =>  $row->pinstance->businessStreetName()  ?? '')->sortable(),
            Column::make('Status', 'pinstance.mobile')
                ->label(function ($row) {
                    return view('business.registrations.includes.approval_status', compact('row'));
                }),
            Column::make('Action', 'id')
                ->view('business.registrations.includes.approval')
        ];
    }
}
