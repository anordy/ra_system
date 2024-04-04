<?php

namespace App\Http\Livewire\Mvr;

use App\Enum\GeneralConstant;
use App\Enum\MvrRegistrationStatus;
use App\Events\SendSms;
use App\Jobs\SendCustomSMS;
use App\Models\MvrPlateNumberStatus;
use App\Models\MvrRegistration;
use App\Models\MvrRegistrationStatusChange;
use App\Traits\CustomAlert;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PlateNumbersTable extends DataTableComponent
{
    use CustomAlert;

    public $plate_number_status;

    protected $listeners = [
        'confirmUpdate'
    ];

    public function mount($plate_number_status)
    {
        $this->plate_number_status = $plate_number_status;
    }

    public function builder(): Builder
    {
        return MvrRegistration::query()
            ->where(['mvr_plate_number_status' => $this->plate_number_status])
            ->orderBy('mvr_registrations.created_at', 'ASC');
    }


    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        $this->setAdditionalSelects(['mvr_plate_number_status']);
    }

    public function columns(): array
    {
        return [
            Column::make(__("Chassis No"), "chassis.chassis_number")
                ->searchable(),
            Column::make(__("Plate No"), "plate_number")
                ->format(function ($value, $row) {
                    return $row->plate_number ?? 'PENDING';
                })
                ->searchable(),
            Column::make(__("Reg Type"), "regtype.name")
                ->searchable(),
            Column::make(__("Plate No Color"), "platecolor.name")
                ->searchable(),
            Column::make(__("Plate No Size"), "platesize.name")
                ->searchable(),
            Column::make(__("Registration Date"), "registered_at")
                ->format(function ($value, $row) {
                    return Carbon::create($row->registered_at)->format('d M Y') ?? 'N/A';
                }),
            Column::make('Action', 'id')
                ->format(function ($value, $row) {
                    if (MvrPlateNumberStatus::STATUS_GENERATED == $row->mvr_plate_number_status) {
                        return <<< HTML
                            <button class="btn btn-outline-primary btn-sm" wire:click="updateToPrinted($value)"><i class="fa fa-edit"></i> Update Status</button>
                        HTML;
                    } elseif (MvrPlateNumberStatus::STATUS_PRINTED == $row->mvr_plate_number_status) {
                        return <<< HTML
                            <button class="btn btn-outline-primary btn-sm" wire:click="updateToReceived($value)"><i class="fa fa-edit"></i> Update Status</button>
                        HTML;
                    } elseif (MvrPlateNumberStatus::STATUS_RECEIVED == $row->mvr_plate_number_status) {
                        $id = encrypt($value);
                        return <<< HTML
                            <button class="btn btn-outline-primary btn-sm" onclick="Livewire.emit('showModal', 'mvr.plate-number-collection-model', '$id')"><i class="fa fa-edit"></i> Update Status</button>
                        HTML;
                    }
                    return '';
                })
                ->html()
        ];
    }

    public function updateToPrinted($id)
    {
        $this->customAlert(GeneralConstant::QUESTION, 'Update Status to <span class="text-uppercase font-weight-bold">Printed</span>?', [
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

    public function updateToReceived($id)
    {
        $this->customAlert(GeneralConstant::QUESTION, 'Update Status to <span class="text-uppercase font-weight-bold">Received</span>?', [
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

    public function updateToCollected($id)
    {
        $this->customAlert(GeneralConstant::QUESTION, 'Update Status to <span class="text-uppercase font-weight-bold">Collected</span>?', [
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

    public function confirmUpdate($value)
    {
        try {
            $data = (object) $value['data'];
            $mvr = MvrRegistration::query()->find($data->id);

            DB::beginTransaction();
            $mvr->update([
                'mvr_plate_number_status' => $data->status
            ]);


            $mvrStatusChange = MvrRegistrationStatusChange::where('registration_number',$mvr->registration_number)->first();
            if ($mvrStatusChange) {
                $mvrStatusChange->mvr_plate_number_status = $data->status;
                $mvrStatusChange->status = MvrRegistrationStatus::STATUS_REGISTERED;
                $mvrStatusChange->save();
            }

            if ($data->status === MvrPlateNumberStatus::STATUS_PRINTED) {
                event(new SendSms(SendCustomSMS::SERVICE, NULL, ['phone' => $mvr->taxpayer->mobile, 'message' => "
                Hello {$mvr->taxpayer->fullname}, your plate number for motor vehicle registration for chassis number {$mvr->chassis->chassis_number} has been printed. You may visit ZRA offices after 3 days for collection of plate number"]));
            }
            DB::commit();
            $this->flash(GeneralConstant::SUCCESS, 'Plate Number Status updated', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('PLATE-NUMBERS-TABLE-CONFIRM-UPDATE', [$e]);
            $this->customAlert(GeneralConstant::WARNING, 'Something went wrong, please contact the administrator for help', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
