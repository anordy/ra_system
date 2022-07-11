<?php

namespace App\Http\Livewire\TaxAgent;

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
		return TaxAgent::where('status', 'approved');
	}

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
			Column::make("Reference No", "reference_no")
				->sortable(),
			Column::make("TIN No", "tin_no")
				->sortable(),
			Column::make("Plot No.", "plot_no")
				->sortable(),
			Column::make("Block", "block")
				->sortable(),
			Column::make("Town", "town")
				->sortable(),
			Column::make("Region", "region")
				->sortable(),
		  Column::make('Status', 'status')
			->view('taxagents.includes.status'),
			Column::make('Action', 'id')
				->view('taxagents.includes.actions')
				->html(true)
		];
	}
}
