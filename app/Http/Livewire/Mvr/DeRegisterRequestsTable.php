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
use App\Models\TaxAgentStatus;
use App\Models\Taxpayer;
use App\Traits\WithSearch;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\TaxAgent;

class DeRegisterRequestsTable extends DataTableComponent
{
    use CustomAlert, WithSearch;

    public $status_id;

    public function builder(): Builder
    {
        if (empty($this->status_id)){
            return MvrDeRegistrationRequest::query();
        }else{
            return MvrDeRegistrationRequest::query()->whereIn('mvr_request_status_id',[$this->status_id]);
        }
    }

    public function mount($status){
        $rq_status = MvrRequestStatus::query()->where(['name'=>$status])->first();
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
            Column::make("Agent Name", "mvr_agent_id")->format(function($id){
                $agent = MvrAgent::query()->find($id);
                return !empty($agent)? $agent->taxpayer->fullname():'';
            }),
            Column::make("Status", "request_status.name")
                ->sortable(),
            Column::make('Action', 'id')
                ->format(function ($value) {
                    $url = route('mvr.de-register-requests.show',encrypt($value));
                    return <<< HTML
                    <a class="btn btn-outline-primary btn-sm" href="$url"><i class="fa fa-eye"></i>View</a>
                HTML;})
                ->html()
        ];
    }



}
