<?php

namespace App\Http\Livewire\Mvr;

use App\Models\MvrMotorVehicle;
use App\Models\MvrRegistrationStatus;
use App\Traits\CustomAlert;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class MotorVehiclesTable extends DataTableComponent
{
	use CustomAlert;

    public $status_id;

    public function builder(): Builder
	{
        if (empty($this->status_id)){
            return MvrMotorVehicle::query();
        }else{
            return MvrMotorVehicle::query()->where(['mvr_registration_status_id'=>$this->status_id]);
        }
	}

    public function mount($status){
        $rq_status = MvrRegistrationStatus::where(['name'=>$status])->first();
        $this->status_id = $rq_status->id ?? '';
    }

	public function configure(): void
    {
        $this->setPrimaryKey('id');
//        $this->setAdditionalSelects(['chassis_number']);
	    $this->setTableWrapperAttributes([
	      'default' => true,
	      'class' => 'table-bordered table-sm',
	    ]);
    }

    public function columns(): array
    {
        return [
            Column::make("Chassis No", "chassis.chassis_number")
                ->sortable(),
	        Column::make("Engine Capacity", "chassis.engine_cubic_capacity")
	          ->sortable(),
           Column::make("Model", "chassis.model_type")
                ->sortable(),
            Column::make("Make", "chassis.make")
                ->sortable(),
            Column::make("Imported From", "chassis.imported_from")
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
