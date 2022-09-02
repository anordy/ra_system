<?php

namespace App\Http\Livewire\Mvr;

use App\Models\MvrMotorVehicle;
use App\Models\MvrRegistrationStatus;
use App\Models\MvrRequestStatus;
use App\Models\TaxAgentStatus;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\TaxAgent;

class MotorVehiclesTable extends DataTableComponent
{
	use LivewireAlert;

    public $status_id;

    public function builder(): Builder
	{
        if (empty($this->status_id)){
            return MvrMotorVehicle::query();
        }else{
            return MvrMotorVehicle::query()->distinct('chassis_number')->where(['mvr_registration_status_id'=>$this->status_id]);
        }
	}

    public function mount($status){
        $rq_status = MvrRegistrationStatus::where(['name'=>$status])->first();
        $this->status_id = $rq_status->id ?? '';
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
            Column::make("Chassis No", "chassis_number")
                ->sortable(),
            Column::make("Axles", "number_of_axle")
                ->sortable(),
            Column::make("YoM", "year_of_manufacture")
                ->sortable(),
	        Column::make("Engine Capacity", "engine_capacity")
	          ->sortable(),
           Column::make("Model", "model.name")
                ->sortable(),
            Column::make("Make", "model.make.name")
                ->sortable(),
            Column::make("Registration Status", "registration_status.name")
                ->sortable(),
            Column::make('Action', 'id')
                ->format(function ($value) {
                    $url = route('mvr.show',encrypt($value));
                    return <<< HTML
                    <a class="btn btn-outline-primary btn-sm" href="$url"><i class="fa fa-eye"></i>View</a>
                HTML;})
                ->html()
        ];
    }



}
