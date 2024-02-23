<?php

namespace App\Http\Livewire\Approval\Mvr;

use App\Services\Api\ZbsInternalService;
use App\Traits\CustomAlert;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ZbsVerification extends Component
{
    use CustomAlert;

    public $chassisNumber, $zbsData;

    public function mount($chassisNumber)
    {
        $this->chassisNumber = $chassisNumber;
        $this->zbsData = [
            'cor_number' => random_int(1000000,9999999),
            'mileage' => random_int(50000,130000),
            'inspection_date' => Carbon::today()
        ];
    }

    public function verifyZbs()
    {
        try {
            $traService = new ZbsInternalService();
            $response = $traService->fetchCorInformation($this->chassisNumber);

            if ($response && $response['data']) {
                $this->zbsData = $response['data'];
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
        return view('livewire.mvr.zbs-verification');
    }
}
