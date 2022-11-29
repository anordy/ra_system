<?php

namespace App\Http\Livewire\Approval;

use App\Models\BusinessStatus;
use App\Services\Api\BpraInternalService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BpraVerification extends Component
{
    use LivewireAlert;

    public $business;
    public $matchesText = 'Match';
    public $notValidText = 'Mismatch';
    public $bpraResponse = [];

    public function mount($business){
        $this->business = $business;
    }

    public function validateBPRANumber()
    {
        
        $bpraService = new BpraInternalService;
        try {
            $this->bpraResponse = $bpraService->getData($this->business);
            
            if ($this->bpraResponse) {
                $this->requestSuccess = true;
                DB::beginTransaction();
                $this->business->bpra_no = $this->bpraResponse['reg_number'];
                $this->business->bpra_verification_status = $this->bpraResponse['reg_number'] === $this->business->reg_no ? BusinessStatus::APPROVED : BusinessStatus::REJECTED;
                $this->business->save();
                DB::commit();
            } else {
                $this->alert('error', 'Something went wrong');
            }
            
        } catch (Exception $e) {
            $this->requestSuccess = false;
            Log::error($e);
            DB::rollBack();
            return $this->alert('error', 'Something went wrong');
        }
    }

    public function compareProperties($kyc_property, $bpra_property)
    {
        $kyc_property = strtolower($kyc_property);
        $bpra_property = strtolower($bpra_property);

        return $kyc_property === $bpra_property ? true : false;
    }

    public function render()
    {
        return view('livewire.approval.bpra-verification');
    }
}
