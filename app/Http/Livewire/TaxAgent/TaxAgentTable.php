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

	protected $listeners = [
	  'confirmed',
	  'toggleStatus'
	];
//	protected $model = TaxAgent::class;


	public function builder(): Builder
	{
		return TaxAgent::where('is_verified', 0);
	}

	public function configure(): void
    {
        $this->setPrimaryKey('id');

	    $this->setTableWrapperAttributes([
	      'default' => true,
	      'class' => 'table-bordered table-sm',
	    ]);

//
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
	        Column::make("Created At", "created_at")
	          ->sortable(),
          Column::make('Action', 'id')
	        ->format(fn ($value) => <<< HTML
                    <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'tax-agent.tax-agent-request-view',$value)"><i class="fa fa-eye"></i> </button>
                    <button class="btn btn-success btn-sm" wire:click="approve($value)"><i class="fa fa-check"></i> </button>
                HTML)
	        ->html(true),
        ];
    }

	public function approve($id)
	{
		$this->alert('warning', 'Are you sure you want to approve this request ?', [
		  'position' => 'center',
		  'toast' => false,
		  'showConfirmButton' => true,
		  'confirmButtonText' => 'Approve',
		  'onConfirmed' => 'toggleStatus',
		  'showCancelButton' => true,
		  'cancelButtonText' => 'Cancel',
		  'timer' => null,
		  'data' => [
			'id' => $id
		  ],

		]);
	}

	public function toggleStatus($value)
	{
		try {
			$data = (object) $value['data'];
			$agent = TaxAgent::find($data->id);
			$agent->is_verified = 1;
			$agent->save();
			$this->flash('success', 'Request approved successfully', [], redirect()->back()->getTargetUrl());

		} catch (Exception $e) {
			report($e);
			$this->alert('warning', 'Something whent wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
		}
	}



}
