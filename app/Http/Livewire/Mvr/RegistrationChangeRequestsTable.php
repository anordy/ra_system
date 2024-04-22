<?php

namespace App\Http\Livewire\Mvr;

use App\Models\MvrAgent;
use App\Models\MvrRegistrationChangeRequest;
use App\Models\MvrRegistrationType;
use App\Traits\CustomAlert;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RegistrationChangeRequestsTable extends DataTableComponent
{
	use CustomAlert;

	public function builder(): Builder
	{
		return MvrRegistrationChangeRequest::query();
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
            Column::make("Chassis No", "current_registration.motor_vehicle.chassis_number")
                ->sortable(),
            Column::make("Current Plate Number", "current_registration.plate_number")
                ->sortable(),
            Column::make("Current Type", "current_registration.registration_type.name")
                ->sortable(),
            Column::make("Requested Type", "requested_registration_type_id")
                ->format(fn($value)=>MvrRegistrationType::query()->find($value)->name)
                ->sortable(),
            Column::make("Request Date", "date")
                ->sortable(),
	        Column::make("Agent Name", "mvr_agent_id")->format(fn($id)=>MvrAgent::query()->find($id)->taxpayer->fullname())
	          ->sortable(),
            Column::make("Status", "request_status.name")
                ->sortable(),
            Column::make('Action', 'id')
                ->format(function ($value) {
                    $url = route('mvr.reg-change-requests.show',encrypt($value));
                    return <<< HTML
                    <a class="btn btn-outline-primary btn-sm" href="$url"><i class="bi bi-eye-fill"></i>View</a>
                HTML;})
                ->html()
        ];
    }



}
