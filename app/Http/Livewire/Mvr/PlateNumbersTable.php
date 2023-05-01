<?php

namespace App\Http\Livewire\Mvr;

use App\Models\MvrMotorVehicle;
use App\Models\MvrMotorVehicleRegistration;
use App\Models\MvrPlateNumberStatus;
use App\Models\MvrRegistrationStatus;
use App\Models\TaxAgentStatus;
use App\Traits\WithSearch;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\TaxAgent;

class PlateNumbersTable extends DataTableComponent
{
	use CustomAlert;

    public $plate_number_status_id;

    protected $listeners = [
        'confirmUpdate'
    ];

    public function builder(): Builder
	{
		return MvrMotorVehicleRegistration::query()
            ->where(['mvr_plate_number_status_id'=>$this->plate_number_status_id]);
	}

    public function mount($plate_number_status){
        $pn_status = MvrPlateNumberStatus::query()->where(['name'=>$plate_number_status])->first();
        $this->plate_number_status_id = $pn_status->id ?? '';
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
            Column::make("Plate Number", "id")->format(function ($id){
                $mvr = MvrMotorVehicleRegistration::query()->find($id);
                return $mvr->current_personalized_registration ? $mvr->current_personalized_registration->plate_number: $mvr->plate_number;
            })
                ->sortable(),
            Column::make("Size", "plate_size.name")
                ->sortable(),
            Column::make("Color", "registration_type.plate_number_color")
                ->sortable(),
            Column::make("Type", "registration_type.name")
                ->sortable(),
            Column::make("Vehicle Chassis No", "motor_vehicle.chassis_number")
                ->sortable(),
           Column::make("Status", "plate_number_status.name")
                ->sortable()
            ->format(function ($value){
                return $value==MvrPlateNumberStatus::STATUS_GENERATED?'TO PRINT':$value;
            }),
            Column::make('Action', 'id')
                ->format(function ($value) {
                    $mvr =   MvrMotorVehicleRegistration::query()->find($value);
                    if (MvrPlateNumberStatus::STATUS_GENERATED==$mvr->plate_number_status->name){
                        return <<< HTML
                            <button class="btn btn-outline-primary btn-sm" wire:click="updateToPrinted($value)"><i class="fa fa-edit"></i> Update Status</button>
                        HTML;
                    }elseif (MvrPlateNumberStatus::STATUS_PRINTED==$mvr->plate_number_status->name){
                        return <<< HTML
                            <button class="btn btn-outline-primary btn-sm" wire:click="updateToReceived($value)"><i class="fa fa-edit"></i> Update Status</button>
                        HTML;
                    }elseif(MvrPlateNumberStatus::STATUS_RECEIVED==$mvr->plate_number_status->name){
                        return <<< HTML
                            <button class="btn btn-outline-primary btn-sm" onclick="Livewire.emit('showModal', 'mvr.plate-number-collection-model',$value)"><i class="fa fa-edit"></i> Update Status</button>
                        HTML;
                    }
                    return '';
                    })
                ->html()
        ];
    }

    public function updateToPrinted($id){
        $this->customAlert('question', 'Update Status to <span class="text-uppercase font-weight-bold">Printed</span>?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Yes',
            'onConfirmed' => 'confirmUpdate',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $id,
                'status' => MvrPlateNumberStatus::STATUS_PRINTED
            ],

        ]);
    }

    public function updateToReceived($id){
        $this->customAlert('question', 'Update Status to <span class="text-uppercase font-weight-bold">Received</span>?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Yes',
            'onConfirmed' => 'confirmUpdate',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $id,
                'status' => MvrPlateNumberStatus::STATUS_RECEIVED
            ],

        ]);
    }

    public function updateToCollected($id){
        $this->customAlert('question', 'Update Status to <span class="text-uppercase font-weight-bold">Collected</span>?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Yes',
            'onConfirmed' => 'confirmUpdate',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $id,
                'status' => MvrPlateNumberStatus::STATUS_ACTIVE
            ],

        ]);
    }

    public function confirmUpdate($value){
        try {
            $data = (object) $value['data'];
            $plate_status = MvrPlateNumberStatus::query()->firstOrCreate(['name'=>$data->status]);
            $mvr = MvrMotorVehicleRegistration::query()->find($data->id);
            $mvr->update([
                'mvr_plate_number_status_id' => $plate_status->id
            ]);
            if ($plate_status->name == MvrPlateNumberStatus::STATUS_ACTIVE){
                MvrMotorVehicleRegistration::query()
                    ->where(['mvr_motor_vehicle_id'=>$mvr->mvr_motor_vehicle_id])
                    ->whereKeyNot($mvr->id)
                    ->update([
                    'mvr_plate_number_status_id' =>  MvrPlateNumberStatus::query()->firstOrCreate(['name'=>MvrPlateNumberStatus::STATUS_RETIRED])->id
                ]);
                //update registration status
                $reg_status = MvrRegistrationStatus::query()
                    ->firstOrCreate(['name'=>MvrRegistrationStatus::STATUS_REGISTERED]);
                MvrMotorVehicle::query()
                    ->find($mvr->mvr_motor_vehicle_id)
                    ->update([
                        'mvr_registration_status_id'=>$reg_status->id
                    ]);

            }
            $this->flash('success', 'Plate Number Status updated', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            report($e);
            $this->customAlert('warning', 'Something went wrong, please contact the administrator for help', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
