<?php

namespace App\Http\Livewire\Mvr;

use App\Models\MvrAgent;
use App\Models\MvrMotorVehicle;
use App\Models\MvrRegistrationStatus;
use App\Models\Taxpayer;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AgentsTable extends DataTableComponent
{
	use LivewireAlert;

    public function builder(): Builder
	{
        return MvrAgent::query();
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
            Column::make("Number", "agent_number")
                ->sortable(),
            Column::make("Name", "taxpayer_id")
                ->format(fn($taxpayer_id)=>Taxpayer::query()->find($taxpayer_id)->fullname())
                ->sortable(),
            Column::make("Phone Numbers", "taxpayer_id")
                ->format(function($taxpayer_id){
                    $taxpayer = Taxpayer::query()->find($taxpayer_id);
                    return $taxpayer->mobile.'/'.$taxpayer->alt_mobile;
                })
                ->sortable(),
            Column::make("email", "taxpayer.email")
                ->sortable(),
           Column::make("TIN", "taxpayer.tin")
                ->sortable(),
            Column::make("Reg. Date", "registration_date")
                ->sortable(),
            Column::make("Status", "status")
                ->sortable(),
            Column::make('Action', 'id')
                ->format(function ($value) {})
                ->html()
        ];
    }



}
