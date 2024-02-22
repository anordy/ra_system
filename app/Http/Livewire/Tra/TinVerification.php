<?php

namespace App\Http\Livewire\Tra;

use App\Models\Tra\Tin;
use App\Services\Api\TraInternalService;
use App\Traits\CustomAlert;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class TinVerification extends Component
{
    use CustomAlert;

    public $tinNumber, $tinData;

    public function mount($tinNumber)
    {
        $this->tinNumber = $tinNumber;
        $this->tinData = Tin::where('tin', $tinNumber)->first();
    }

    public function verifyTin()
    {
        try {
            $traService = new TraInternalService();
            $response = $traService->getTinNumber($this->tinNumber);

            if ($response && $response['data']) {
                $this->tinData = $response['data'];
            } else if ($response && $response['data'] == null) {
                $this->customAlert('warning', $response['message']);
                return;
            } else {
                $this->customAlert('error', 'Something went wrong');
                return;
            }

        } catch (Exception $e) {
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            return;
        }
    }


    public function render()
    {
        return view('livewire.tra.tin-verification');
    }
}
