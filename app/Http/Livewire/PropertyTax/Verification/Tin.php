<?php

namespace App\Http\Livewire\PropertyTax\Verification;


use App\Services\Api\TraInternalService;
use App\Traits\CustomAlert;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Tin extends Component
{
    use CustomAlert;

    public $responsiblePerson;
    public $is_verified_triggered = false;
    public $tin;

    public function mount($responsiblePerson)
    {
        $this->responsiblePerson = $responsiblePerson;
    }

    public function verifyTIN()
    {
        try {
            $this->is_verified_triggered = true;
            $traService = new TraInternalService();
            $response = $traService->getTinNumber($this->responsiblePerson->id_number);
            if ($response && $response['data']) {
                $this->tin = $response['data'];
                $this->approve();
            } else if ($response && $response['data'] == null) {
                $this->customAlert('warning', $response['message']);
                return;
            } else {
                $this->customAlert('error', 'Something went wrong');
                return;
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            return;
        }
    }

    public function approve()
    {
        try {
            $this->responsiblePerson->update([
                'first_name' => $this->convertStringToCamelCase($this->tin['first_name']),
                'middle_name' => $this->convertStringToCamelCase($this->tin['middle_name']),
                'last_name' => $this->convertStringToCamelCase($this->tin['last_name']),
                'id_verified_at' => Carbon::now()->toDateTimeString(),
                'email' => $this->convertStringToCamelCase($this->tin['email']),
                'mobile' => $this->convertStringToCamelCase($this->tin['mobile']),
                'gender' => $this->convertStringToCamelCase($this->tin['gender']),
            ]);
            $this->customAlert('success', 'TIN has been verified');
        } catch (Exception $e) {
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', 'Failed to verify TIN please try again later!');
        }
    }


    public function convertStringToCamelCase($string)
    {
        return ucfirst(strtolower($string));
    }

    public function render()
    {
        return view('livewire.property-tax.verification.tin');
    }
}
