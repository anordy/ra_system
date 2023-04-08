<?php

namespace App\Http\Livewire\TaxAgent\Renew;

use App\Models\RenewTaxAgentRequest;
use App\Models\TaxAgentStatus;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class VerifiedTable extends DataTableComponent
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
        $this->setAdditionalSelects(['tax_agent_id', 'taxpayer_id']);
    }

    public function builder(): Builder
    {
        return RenewTaxAgentRequest::query()
            ->where('renew_tax_agent_requests.status', TaxAgentStatus::VERIFIED)
            ->with('tax_agent');
    }

    public function columns(): array
    {
        return [
            Column::make("Tax Payer", "tax_agent.taxpayer.first_name")
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->tax_agent->taxpayer->first_name} {$row->tax_agent->taxpayer->middle_name} {$row->tax_agent->taxpayer->last_name}";
                }),
            Column::make("Reference No", "tax_agent.reference_no")
                ->sortable(),
            Column::make("TIN No", "tax_agent.tin_no")
                ->sortable(),
            Column::make("Plot No.", "tax_agent.plot_no")
                ->sortable(),
            Column::make("Block", "tax_agent.block")
                ->sortable(),
            Column::make("District", "tax_agent.district.name")
                ->sortable(),
            Column::make("Region", "tax_agent.region.name")
                ->sortable(),
            Column::make("Status", "status")
                ->view('taxagents.renew.includes.renewal_status'),
            Column::make('Action', 'id')
                ->view('taxagents.renew.includes.renewal_actions')

        ];
    }


}
