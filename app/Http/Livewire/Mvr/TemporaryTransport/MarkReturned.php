<?php

namespace App\Http\Livewire\Mvr\TemporaryTransport;

use App\Enum\AlertType;
use App\Enum\CustomMessage;
use App\Enum\MvrRegistrationStatus;
use App\Enum\MvrTemporaryTransportStatus;
use App\Models\MvrTemporaryTransport;
use App\Traits\CustomAlert;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class MarkReturned extends Component
{
    use CustomAlert;

    public $mvrRegistration, $mvrTemporaryId, $temporaryTransport;

    public function mount($mvrTemporaryId)
    {
        $this->temporaryTransport = MvrTemporaryTransport::findOrFail(decrypt($mvrTemporaryId));
    }


    public function submit()
    {
        try {
            DB::beginTransaction();

            $payload = [
                'status' => MvrTemporaryTransportStatus::RETURNED
            ];

            $transport = $this->temporaryTransport->update($payload);

            if (!$transport) {
                throw new Exception('Failed to update transport');
            }

            $this->temporaryTransport->mvr->status = MvrRegistrationStatus::STATUS_REGISTERED;

            if (!$this->temporaryTransport->mvr->save()) throw new Exception('Failed to update registration status');

            DB::commit();
            $this->customAlert('success', __('This motor vehicle has been returned.'));
            return redirect()->route('mvr.temporary-transports.index');

        } catch (Exception $exception) {
            DB::rollBack();
            Log::error('MVR-TEMPORARY-TRANSPORT-EXTEND-SUBMIT', [$exception]);
            $this->customAlert(AlertType::ERROR, CustomMessage::ERROR);
        }
    }

    public function render()
    {
        return view('livewire.mvr.temporary-transports.returned');
    }
}
