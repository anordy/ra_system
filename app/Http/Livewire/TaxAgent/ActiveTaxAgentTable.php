<?php

namespace App\Http\Livewire\TaxAgent;

use App\Models\TaxAgentStatus;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\TaxAgent;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;

class ActiveTaxAgentTable extends DataTableComponent
{
	//    protected $model = TaxAgent::class;

	public function builder(): Builder
	{
        return TaxAgent::query()->where('status', '=', TaxAgentStatus::APPROVED)
            ->with('region', 'district','taxpayer');
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
			Column::make("Reference No", "reference_no")
				->sortable()->searchable(),
			Column::make("TIN No", "tin_no")
				->sortable(),
			Column::make("Plot No.", "plot_no")
				->sortable()->searchable(),
			Column::make("Block", "block")
				->sortable()->searchable(),
            Column::make("District", "district.name")
                ->sortable()->searchable(),
            Column::make("Region", "region.name")
                ->sortable()->searchable(),
		  Column::make('Status', 'status')
			->view('taxagents.includes.status'),
			Column::make('Action', 'id')
				->view('taxagents.includes.actions')
				->html(true)
		];
	}
}
