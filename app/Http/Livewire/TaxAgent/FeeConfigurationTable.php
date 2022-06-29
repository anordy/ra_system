<?php

namespace App\Http\Livewire\TaxAgent;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\TaPaymentConfiguration;

class FeeConfigurationTable extends DataTableComponent
{
    protected $model = TaPaymentConfiguration::class;

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
            Column::make("Category", "category")
                ->sortable(),
            Column::make("duration", "duration")
                ->sortable(),
	        Column::make('Amount', 'amount')
	          ->sortable(),
            Column::make("No of days/months/years", "no_of_days")
                ->sortable(),
        ];
    }
}
