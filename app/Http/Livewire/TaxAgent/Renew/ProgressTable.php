<?php

namespace App\Http\Livewire\TaxAgent\Renew;

use App\Models\RenewTaxAgentRequest;
use App\Models\TaxAgentStatus;
use App\Models\WorkflowTask;
use App\Traits\WithSearch;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ProgressTable extends DataTableComponent
{
    use CustomAlert;

    //    protected $model = RenewTaxAgentRequest::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['pinstance_type']);
    }

    public function builder(): Builder
    {
        return WorkflowTask::with('pinstance', 'user')
            ->where('pinstance_type', RenewTaxAgentRequest::class)
            ->where('status', '!=', WorkflowTask::COMPLETED)
            ->where('owner', WorkflowTask::STAFF);
    }

    public function columns(): array
    {
        return [
            Column::make('Tax Payer', 'pinstance.id')
                ->label(fn ($row) => $row->pinstance->tax_agent->taxpayer->first_name . ' ' . $row->pinstance->tax_agent->taxpayer->middle_name . ' ' . $row->pinstance->tax_agent->taxpayer->last_name ?? '')
                ->sortable()
                ->searchable(),
            Column::make('Reference No', 'pinstance.reference_no')
                ->label(fn ($row) => $row->pinstance->tax_agent->reference_no)
                ->sortable()
                ->searchable(),
            Column::make('TIN No', 'pinstance.tin_no')
                ->label(fn ($row) => $row->pinstance->tax_agent->tin_no)
                ->sortable()
                ->searchable(),
            Column::make('District', 'pinstance.district')
                ->label(fn ($row) => $row->pinstance->tax_agent->district->name ?? 'N/A')
                ->sortable()
                ->searchable(),
            Column::make('Region', 'pinstance.region')
                ->label(fn ($row) => $row->pinstance->tax_agent->region->name ?? '')
                ->sortable()
                ->searchable(),
            Column::make('From State', 'from_place')
                ->format(fn ($value) => str_replace('_', ' ', strtoupper($value)))
                ->sortable()
                ->searchable(),
            Column::make('Current State', 'to_place')
                ->format(fn ($value) => str_replace('_', ' ', strtoupper($value)))
                ->sortable()
                ->searchable(),
            Column::make("Status", "status")
                ->sortable()->searchable(),
            Column::make('Action', 'pinstance_id')
                ->view('taxagents.renew.includes.renewal_actions')

        ];
    }
}
