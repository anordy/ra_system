<?php

namespace App\Http\Livewire\Approval;

use App\Models\BusinessDirector;
use App\Models\BusinessShare;
use App\Models\BusinessShareholder;
use App\Models\BusinessStatus;
use App\Services\Api\BpraInternalService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
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
    public $shareholders;
    public $directors;
    public $shares;

    public function mount($business){
        $this->business = $business;
    }

    public function validateBPRANumber()
    {
        
        $bpraService = new BpraInternalService;
        try {
            $this->bpraResponse = $bpraService->getData($this->business);
            
            if ($this->bpraResponse['businessData']) {
                $this->requestSuccess = true;

                $this->directors = $this->bpraResponse['directors'];
                $this->shareholders = $this->bpraResponse['shareHolders'];
                $this->shares = $this->bpraResponse['listShareHolderShares'];
            } else {
                $this->alert('error', 'Something went wrong, Please contact our support desk for help');
            }
            
        } catch (Exception $e) {
            $this->requestSuccess = false;
            Log::error($e);
            DB::rollBack();
            return $this->alert('error', 'Something went wrong, Please contact our support desk for help');
        }
    }

    public function compareProperties($kyc_property, $bpra_property)
    {
        $kyc_property = strtolower($kyc_property);
        $bpra_property = strtolower($bpra_property);

        return $kyc_property === $bpra_property ? true : false;
    }

    public function confirm(){
        try {
            DB::beginTransaction();
            if ($this->bpraResponse['businessData']['reg_number']) {
                $this->business->bpra_no = $this->bpraResponse['businessData']['reg_number'];
            }
            $this->business->authorities_verified_at = Carbon::now();
            $this->business->bpra_verification_status = BusinessStatus::APPROVED;
            $this->business->save();

            if ($this->directors) {
                BusinessDirector::insert($this->directors);
            }
            
            if ($this->shareholders) {
                BusinessShareholder::insert($this->shareholders);
            }
            
            if ($this->shareholders) {
                foreach ($this->shares as $share) {
                    $shareHolderID = BusinessShareholder::where('national_id', $share['item'])->value('id');
    
                    BusinessShare::create([
                        'business_id' =>$this->business->id,
                        'share_holder_id' =>$shareHolderID,
                        'shareholder_name' => $share['shareholder_name'],
                        'share_class' => $share['share_class'],
                        'number_of_shares' => $share['number_of_shares'],
                        'currency' => $share['currency'],
                        'number_of_shares_taken' => $share['number_of_shares_taken'],
                        'number_of_shares_paid' => $share['number_of_shares_paid'],
                    ]);
                }
            }
             
            DB::commit();
            $this->alert('success', 'Bpra Verification Completed.');
        } catch (\Throwable $e) {
            Log::error($e .','. Auth::user());
            $this->alert('error', 'Something went wrong, Please contact our support desk for help');
        }
    }


    public function continueWithProvidedData(){
        try {
            DB::beginTransaction();
            $this->business->bpra_verification_status = BusinessStatus::PBRA_UNVERIFIED;
            $this->business->save();
             
            DB::commit();
            $this->alert('success', 'Continue with provided data successfully.');
        } catch (\Throwable $e) {
            Log::error($e .','. Auth::user());
            $this->alert('error', 'Something went wrong, Please contact our support desk for help');
        }
    }

    public function render()
    {
        return view('livewire.approval.bpra-verification');
    }
}
