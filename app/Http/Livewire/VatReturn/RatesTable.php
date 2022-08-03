<?php

namespace App\Http\Livewire\VatReturn;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\VatReturn\VatConfigRate;

class RatesTable extends DataTableComponent
{
//    protected $model = VatConfigRate::class;

public function builder(): Builder
{
	return VatConfigRate::query();
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
          Column::make('Vat Category', 'vatCategory.name')
	        ->sortable(),
		  Column::make('Code', 'vat_category_code')
          ->sortable(),
            Column::make("Rate (%)", "rate")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }
}
