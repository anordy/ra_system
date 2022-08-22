<?php

namespace App\Http\Livewire\Mvr;

use App\Models\MvrAgent;
use App\Models\MvrDeRegistrationRequest;
use App\Models\MvrMotorVehicle;
use App\Models\MvrOwnershipTransfer;
use App\Models\MvrRegistrationChangeRequest;
use App\Models\MvrRegistrationStatus;
use App\Models\MvrRegistrationType;
use App\Models\MvrRequestStatus;
use App\Models\MvrWrittenOff;
use App\Models\TaxAgentStatus;
use App\Models\Taxpayer;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\TaxAgent;

class WrittenOffMotorVehiclesTable extends DataTableComponent
{
    use LivewireAlert;


    public function builder(): Builder
    {
        return MvrWrittenOff::query();
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
            Column::make("Date", "date")
                ->sortable(),
            Column::make('Action', 'mvr_motor_vehicle_id')
                ->format(function ($value) {
                    $url = route('mvr.show',encrypt($value));
                    return <<< HTML
                    <a class="btn btn-outline-primary btn-sm" href="$url"><i class="fa fa-eye"></i>View</a>
                HTML;})
                ->html()
        ];
    }



}
