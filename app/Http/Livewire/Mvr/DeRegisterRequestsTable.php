<?php

namespace App\Http\Livewire\Mvr;

use App\Models\MvrDeRegistrationRequest;
use App\Models\MvrMotorVehicle;
use App\Models\MvrRegistrationChangeRequest;
use App\Models\MvrRegistrationStatus;
use App\Models\MvrRegistrationType;
use App\Models\TaxAgentStatus;
use App\Models\Taxpayer;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\TaxAgent;

class DeRegisterRequestsTable extends DataTableComponent
{
    use LivewireAlert;

    public function builder(): Builder
    {
        return MvrDeRegistrationRequest::query();
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
            Column::make("Chassis No", "motor_vehicle.chassis_number")
                ->sortable(),
            Column::make("Plate Number", "mvr_motor_vehicle_id")
                ->format(fn($mv_id)=>MvrMotorVehicle::query()->find($mv_id)->current_registration->plate_number)
                ->sortable(),
            Column::make("Reg Type", "mvr_motor_vehicle_id")
                ->format(fn($mv_id)=>MvrMotorVehicle::query()->find($mv_id)->current_registration->registration_type->name)
                ->sortable(),
            Column::make("Received Date", "date_received")
                ->sortable(),
            Column::make("Agent Z-Number", "agent.reference_no")
                ->sortable(),
            Column::make("Agent Name", "agent_taxpayer_id")->format(fn($id)=>Taxpayer::query()->find($id)->fullname())
                ->sortable(),
            Column::make("Status", "request_status.name")
                ->sortable(),
            Column::make('Action', 'id')
                ->format(function ($value) {
                    $url = route('mvr.de-register-requests.show',encrypt($value));
                    return <<< HTML
                    <a class="btn btn-info btn-sm" href="$url"><i class="fa fa-eye"></i>View</a>
                HTML;})
                ->html()
        ];
    }



}
