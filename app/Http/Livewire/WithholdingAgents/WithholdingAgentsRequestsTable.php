<?php

namespace App\Http\Livewire\WithholdingAgents;

use App\Enum\WithholdingAgentStatus;
use App\Models\TaxAgent;
use App\Models\TaxAgentStatus;
use App\Models\WithholdingAgent;
use App\Traits\WithSearch;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Carbon\Carbon;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class WithholdingAgentsRequestsTable extends DataTableComponent
{
    use CustomAlert;

    public function builder(): Builder
    {
        return  WithholdingAgent::query()
            ->where('app_status',WithholdingAgentStatus::PENDING)->orderByDesc('id');
          
    }

    protected $listeners = [
        'confirmed',
        'toggleStatus'
    ];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('WA Number', 'wa_number')
                ->sortable()
                ->searchable(),
            Column::make('Institution Name', 'institution_name')
                ->sortable()
                ->searchable(),
            Column::make('E-mail', 'email')
                ->sortable()
                ->searchable(),
            Column::make('Mobile', 'mobile')
                ->sortable()
                ->searchable(),
            Column::make('Commencing Date', 'date_of_commencing')
                ->format(function($value, $row) { return Carbon::create($row->date_of_commencing)->toFormattedDateString(); })
                ->sortable(),
            Column::make('Status', 'app_status')
                ->view('withholding-agent.includes.status'),
            Column::make('Action', 'id')
                ->view('withholding-agent.includes.waActions'),
        ];
    }
}
