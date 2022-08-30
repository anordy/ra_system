<?php

namespace App\Http\Livewire\TaxAgent;

use App\Models\TaxAgentStatus;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\TaxAgent;

class TaxAgentTable extends DataTableComponent
{
	use LivewireAlert;

	public function builder(): Builder
	{
		return TaxAgent::query()->where('status', '=', TaxAgentStatus::VERIFIED)->with('region', 'district')->orderByDesc('id');
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
            Column::make("Tax Payer", "taxpayer.first_name")
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->taxpayer->first_name} {$row->taxpayer->middle_name} {$row->taxpayer->last_name}";
                }),
            Column::make("TIN No", "tin_no")
                ->sortable(),
            Column::make("Town", "district.name")
                ->sortable(),
            Column::make("Region", "region.name")
                ->sortable(),
	        Column::make("Created At", "created_at")
	          ->sortable(),
	        Column::make('Status', 'status')
          ->view('taxagents.includes.status'),
          Column::make('Action', 'id')
	        ->view('taxagents.includes.actionReq')

        ];
    }



}
