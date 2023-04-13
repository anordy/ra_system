<?php

namespace App\Http\Livewire\Mvr;

use App\Models\MvrMotorVehicle;
use App\Models\MvrRegistrationStatus;
use App\Models\TaxAgentStatus;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\TaxAgent;

class RegisteredMotorVehiclesTable extends DataTableComponent
{
	use CustomAlert;

	public function builder(): Builder
	{
        $in_statuses = MvrRegistrationStatus::query()
            ->whereIn('name',[MvrRegistrationStatus::STATUS_REGISTERED])
            ->pluck('id')
            ->toArray();
		return MvrMotorVehicle::query()->distinct()->whereIn('mvr_registration_status_id',$in_statuses);
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
            Column::make("Plate Number", "id")
                ->format(fn($id)=>MvrMotorVehicle::query()->find($id)->current_registration->plate_number??'')
                ->sortable(),
            Column::make("Registration Type", "id")
                ->format(fn($id)=>MvrMotorVehicle::query()->find($id)->current_registration->registration_type->name??'')
                ->sortable(),
            Column::make("Date", "registration_date")
                ->format(fn($id)=>MvrMotorVehicle::query()->find($id)->current_registration->registration_type->name??'')
                ->sortable(),
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
