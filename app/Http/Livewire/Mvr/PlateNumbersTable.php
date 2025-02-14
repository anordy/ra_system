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
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PlateNumbersTable extends Component
{
    use CustomAlert;

    public $plate_number_status;
    public $selectedItems = [];

    protected $listeners = [
        'confirmUpdate'
    ];

    public function mount($plate_number_status)
    {
        $this->plate_number_status = $plate_number_status;
    }


    public function printed($mvrId){
        try {
            DB::beginTransaction();
            $this->updatePrinted($mvrId);
            DB::commit();
            return redirect()->route('mvr.plate-numbers')->with('success', 'Plate number have been updated as printed.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('PLATE-NUMBERS-TABLE-CONFIRM-UPDATE', [$e]);
            $this->customAlert(GeneralConstant::WARNING, 'Something went wrong, please contact the administrator for help', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }

    public function printedBulk(){
        try {
            $selectedIds = array_keys($this->selectedItems, true);
            DB::beginTransaction();
            foreach ($selectedIds as $selectedId) {
                $this->updatePrinted($selectedId);
            }
            DB::commit();
            return redirect()->route('mvr.plate-numbers')->with('success', 'Plate numbers have been updated as printed.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('PLATE-NUMBERS-TABLE-CONFIRM-UPDATE', [$e]);
            $this->customAlert(GeneralConstant::WARNING, 'Something went wrong, please contact the administrator for help', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }

    public function updatePrinted($mvrId){
        $mvr = MvrRegistration::findOrFail($mvrId);
        $mvr->update([
            'mvr_plate_number_status' => MvrPlateNumberStatus::STATUS_PRINTED
        ]);

        $mvrStatusChange = MvrRegistrationStatusChange::where('registration_number', $mvr->registration_number)->first();
        if ($mvrStatusChange) {
            $mvrStatusChange->mvr_plate_number_status = MvrPlateNumberStatus::STATUS_PRINTED;
            $mvrStatusChange->status = MvrRegistrationStatus::STATUS_REGISTERED;
            $mvrStatusChange->save();
        }

        event(new SendSms(SendCustomSMS::SERVICE, NULL, ['phone' => $mvr->taxpayer->mobile, 'message' => "Hello {$mvr->taxpayer->fullname}, your plate number for motor vehicle registration for chassis number {$mvr->chassis->chassis_number} has been printed. You may visit ZRA offices after 3 days for collection of plate number"]));
    }

    public function received($mvrId){
        try {
            $this->updateReceived($mvrId);
            return redirect()->route('mvr.plate-numbers')->with('success', 'Plate number have been updated as received.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('PLATE-NUMBERS-TABLE-CONFIRM-UPDATE', [$e]);
            $this->customAlert(GeneralConstant::WARNING, 'Something went wrong, please contact the administrator for help', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }

    public function receivedBulk(){
        try{
            $selectedIds = array_keys($this->selectedItems, true);
            foreach ($selectedIds as $selectedId) {
                $this->updateReceived($selectedId);
            }
            return redirect()->route('mvr.plate-numbers')->with('success', 'Plate numbers have been updated as received.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('PLATE-NUMBERS-TABLE-CONFIRM-UPDATE', [$e]);
            $this->customAlert(GeneralConstant::WARNING, 'Something went wrong, please contact the administrator for help', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }

    public function updateReceived($mvrId){
        $mvr = MvrRegistration::findOrFail($mvrId);
        $mvr->update([
            'mvr_plate_number_status' => MvrPlateNumberStatus::STATUS_RECEIVED
        ]);

        $mvrStatusChange = MvrRegistrationStatusChange::where('registration_number', $mvr->registration_number)->first();
        if ($mvrStatusChange) {
            $mvrStatusChange->mvr_plate_number_status = MvrPlateNumberStatus::STATUS_RECEIVED;
            $mvrStatusChange->status = MvrRegistrationStatus::STATUS_REGISTERED;
            $mvrStatusChange->save();
        }
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

    public function render(){
        $plateNumbers = MvrRegistration::query()
            ->where('mvr_plate_number_status', $this->plate_number_status)
            ->orderBy('mvr_registrations.created_at', 'ASC')
            ->latest()
            ->paginate(15);

        return view('livewire.mvr.plate-number-table', [
            'plateNumbers' => $plateNumbers
        ]);
    }
}
