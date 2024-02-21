<?php

namespace App\Http\Livewire\Tra;

use App\Services\Api\TraInternalService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class TinVerification extends Component
{
    use CustomAlert;

    public $tinData, $tinNumber;

    public function mount(){

    }

    public function verifyTin()
    {
        $this->bpraResponse = [];
        $traService = new TraInternalService();
        try {
            $response = $traService->getData($this->business);
            if ($response['message'] == 'successful') {
                $this->requestSuccess = true;
                $this->bpraResponse = $response['data'];
                $this->directors = $this->bpraResponse['directors'];
                $this->shareholders = $this->bpraResponse['shareHolders'];
                $this->shares = $this->bpraResponse['listShareHolderShares'];
            } else if ($response['message'] == 'unsuccessful') {
                $this->customAlert('error', 'BPRA Number does not exist!');
            }else if ($response['message'] = 'unsuccessful') {
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            }

        } catch (Exception $e) {
            $this->requestSuccess = false;
            DB::rollBack();
            Log::error($e);
            return $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }


    public function render()
    {
        return view('livewire.tra.tin-verification');
    }
}
