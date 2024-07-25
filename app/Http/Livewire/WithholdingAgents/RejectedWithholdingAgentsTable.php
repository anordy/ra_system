<?php

namespace App\Http\Livewire\WithholdingAgents;

use App\Enum\WithholdingAgentStatus;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\WithholdingAgent;
use Carbon\Carbon;

class RejectedWithholdingAgentsTable extends DataTableComponent
{

	public function builder(): Builder
	{
     return  WithholdingAgent::query()->where('app_status',WithholdingAgentStatus::REJECTED)->orderByDesc('id');
	}

	public function configure(): void
	{
		$this->setPrimaryKey('id');

		$this->setTableWrapperAttributes([
			'default' => true,
			'class' => 'table-bordered table-sm',
		]);
        $this->setAdditionalSelects(['taxpayer_id']);
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
        ];
	}
}
