<?php

namespace App\Http\Livewire\TaxAgent;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\TaxAgent;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;

class RenewalRequests extends DataTableComponent
{
    protected $model = TaxAgent::class;

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
		  Column::make('Action', 'id')
			->label(function ($row) {
				return <<< HTML
                        <button data-toggle="tooltip" data-placement="right" title="View" class="btn btn-info btn-sm" wire:click="view($row->id)"><i class="fa fa-eye"></i></button>
                    HTML;
			})
			->html(true),
		];
	}
}
