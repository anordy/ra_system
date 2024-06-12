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
use App\Traits\CustomAlert;
use Livewire\Component;

class BpraVerification extends Component
{
    use CustomAlert;

    public $business;
    public $matchesText = 'Match';
    public $notValidText = 'Mismatch';
    public $bpraResponse;
    public $shareholders;
    public $directors;
    public $shares;
    public $requestSuccess;

    public function mount($business)
    {
        $this->business = $business;
    }

    public function validateBPRANumber()
    {
        $this->bpraResponse = [];
        $bpraService = new BpraInternalService;
        try {
            $response = $bpraService->getData($this->business);
            if ($response['message'] == 'successful') {
                $this->requestSuccess = true;
                $this->bpraResponse = $response['data'];
                $this->directors = $this->bpraResponse['directors'];
                $this->shareholders = $this->bpraResponse['shareHolders'];
                $this->shares = $this->bpraResponse['listShareHolderShares'];
            } else if ($response['message'] == 'unsuccessful') {
                $this->customAlert('error', 'BPRA Number does not exist!');
            } else if ($response['message'] = 'unsuccessful') {
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            }
        } catch (Exception $e) {
            $this->requestSuccess = false;
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function compareProperties($kyc_property, $bpra_property)
    {
        $kyc_property = strtolower($kyc_property);
        $bpra_property = strtolower($bpra_property);

        return $kyc_property === $bpra_property ? true : false;
    }

    public function confirm()
    {
        try {
            DB::beginTransaction();
            if ($this->bpraResponse['businessData']['reg_number']) {
                $this->business->bpra_no = $this->bpraResponse['businessData']['reg_number'];
            }
            if ($this->bpraResponse['businessData']['business_name']) {
                $this->business->trading_name = $this->bpraResponse['businessData']['business_name'];
            }
            $this->business->authorities_verified_at = Carbon::now();
            $this->business->bpra_verification_status = BusinessStatus::APPROVED;
            $this->business->save();

            if ($this->directors) {
                foreach ($this->directors as $director) {
                    BusinessDirector::create([
                        'business_id' => $director['business_id'],
                        'country' => $director['country'],
                        'birth_date' => $director['birth_date'],
                        'first_name' => $director['first_name'],
                        'middle_name' => $director['middle_name'],
                        'last_name' => $director['last_name'],
                        'gender' => $director['gender'],
                        'nationality' => $director['nationality'],
                        'national_id' => $director['national_id'],
                        'city_name' => $director['city_name'],
                        'zip_code' => $director['zip_code'],
                        'first_line' => $director['first_line'],
                        'second_line' => $director['second_line'],
                        'third_line' => $director['third_line'],
                        'email' => $director['email'],
                        'mob_phone' => $director['mob_phone'],
                        'created_at' => $director['created_at'],
                    ]);
                }
            }

            if ($this->shareholders) {
                foreach ($this->shareholders as $shareholder) {
                    BusinessShareholder::create([
                        'business_id' => $shareholder['business_id'],
                        'country' => $shareholder['country'],
                        'birth_date' => $shareholder['birth_date'],
                        'first_name' => $shareholder['first_name'],
                        'middle_name' => $shareholder['middle_name'],
                        'last_name' => $shareholder['last_name'],
                        'gender' => $shareholder['gender'],
                        'nationality' => $shareholder['nationality'],
                        'national_id' => $shareholder['national_id'],
                        'city_name' => $shareholder['city_name'],
                        'zip_code' => $shareholder['zip_code'],
                        'first_line' => $shareholder['first_line'],
                        'second_line' => $shareholder['second_line'],
                        'third_line' => $shareholder['third_line'],
                        'email' => $shareholder['email'],
                        'mob_phone' => $shareholder['mob_phone'],
                        'entity_name' => $shareholder['entity_name'],
                        'full_address' => $shareholder['full_address'],
                        'created_at' => $shareholder['created_at'],
                    ]);
                }
            }

            if ($this->shareholders) {
                foreach ($this->shares as $share) {
                    $shareHolderID = BusinessShareholder::where('entity_name', trim($share['shareholder_name']))
                        ->orWhere('national_id', $share['item'])->first();

                    if (!$shareHolderID) {
                        Log::info('NO SHAREHOLDER ID');
                    } else {
                        $shareHolderID = $shareHolderID->id;
                    }

                    BusinessShare::create([
                        'business_id' => $this->business->id,
                        'share_holder_id' => $shareHolderID ?? 0,
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
            $this->customAlert('success', 'Bpra Verification Completed.');
        } catch (\Throwable $e) {
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }


    public function continueWithProvidedData()
    {
        try {
            DB::beginTransaction();
            $this->business->bpra_verification_status = BusinessStatus::PBRA_UNVERIFIED;
            $this->business->save();

            DB::commit();
            $this->customAlert('success', 'Continue with provided data successfully.');
        } catch (\Throwable $e) {
            Log::error($e . ',' . Auth::user());
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.approval.bpra-verification');
    }
}
