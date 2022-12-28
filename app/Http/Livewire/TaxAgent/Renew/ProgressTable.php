<?php

namespace App\Http\Livewire\TaxAgent\Renew;

use App\Models\RenewTaxAgentRequest;
use App\Models\TaxAgentStatus;
use App\Models\WorkflowTask;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ProgressTable extends DataTableComponent
{
    use LivewireAlert;

//    protected $model = RenewTaxAgentRequest::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['tax_agent_id', 'taxpayer_id']);
    }

    public function builder(): Builder
    {
            return WorkflowTask::with('pinstance', 'user')
            ->where('pinstance_type', RenewTaxAgentRequest::class)
            ->where('status', '!=', 'completed')
            ->where('owner', 'staff');
    }

    public function columns(): array
    {
        return [
                Column::make('pinstance_id', 'pinstance_id')->hideIf(true),
                Column::make('Business Category', 'pinstance.category.name')
                    ->label(fn($row) => $row->pinstance->category->name ?? 'N/A')
                    ->sortable()
                    ->searchable(),
                Column::make('Business Type', 'pinstance.business_type')
                    ->label(fn($row) => strtoupper($row->pinstance->business_type ?? 'N/A'))
                    ->sortable()
                    ->searchable(),
                Column::make('Business Name', 'pinstance.name')
                    ->label(fn($row) => $row->pinstance->name ?? 'N/A')
                    ->sortable()
                    ->searchable(),
                Column::make('TIN', 'pinstance.tin')
                    ->label(fn($row) => $row->pinstance->tin ?? '')
                    ->sortable()
                    ->searchable(),
                Column::make('Buss. Reg. No.', 'pinstance.reg_no')
                    ->label(fn($row) => $row->pinstance->reg_no ?? 'N/A')
                    ->sortable()
                    ->searchable(),
                Column::make('Mobile', 'pinstance_type')
                    ->label(fn($row) => $row->pinstance->mobile ?? '')
                    ->sortable()
                    ->searchable(),
                Column::make('From State', 'from_place')
                    ->format(fn($value) => strtoupper($value))
                    ->sortable()
                    ->searchable(),
                Column::make('Current State', 'to_place')
                    ->format(fn($value) => strtoupper($value))
                    ->sortable()
                    ->searchable(),
            Column::make("Status", "status")
                ->view('taxagents.renew.includes.renewal_status'),
            Column::make('Action', 'id')
                ->view('taxagents.renew.includes.renewal_actions')

        ];
    }

}
