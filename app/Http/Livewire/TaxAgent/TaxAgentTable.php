<?php

namespace App\Http\Livewire\TaxAgent;

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
		return TaxAgent::query()->where('status', '=', 'pending');
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
            Column::make("TIN No", "tin_no")
                ->sortable(),
          Column::make("Town", "town")
	        ->sortable(),
          Column::make("Region", "region")
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
