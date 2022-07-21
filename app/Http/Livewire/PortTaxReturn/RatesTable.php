<?php

namespace App\Http\Livewire\PortTaxReturn;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\PortTaxReturn\PortTaxConfigRate;

class RatesTable extends DataTableComponent
{
//    protected $model = PortTaxConfigRate::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
	    $this->setTableWrapperAttributes([
	      'default' => true,
	      'class' => 'table-bordered table-sm',
	    ]);
    }

	public function builder(): Builder
	{
		return PortTaxConfigRate::query();
	}

	public function columns(): array
    {
	    return [
		  Column::make('Service', 'portTaxCategory.portTaxService.name')
	      ->sortable(),
	      Column::make('Category', 'portTaxCategory.name')
		    ->sortable(),
	      Column::make('Code', 'port_tax_category_code')
		    ->sortable(),
	      Column::make('Rate')
	      ->view('livewire.port-tax-return.includes.rates'),
	      Column::make("Created at", "created_at")
		    ->sortable(),
	    ];
    }
}
