<?php

namespace App\Http\Livewire\Mvr;


use App\Models\Country;
use App\Models\MvrBodyType;
use App\Models\MvrClass;
use App\Models\MvrColor;
use App\Models\MvrFuelType;
use App\Models\MvrMake;
use App\Models\MvrModel;
use App\Models\MvrMotorVehicle;
use App\Models\MvrMotorVehicleOwner;
use App\Models\MvrOwnershipStatus;
use App\Models\MvrOwnershipTransfer;
use App\Models\MvrOwnershipTransferReason;
use App\Models\MvrRegistrationStatus;
use App\Models\MvrRequestStatus;
use App\Models\MvrTransmissionType;
use App\Models\MvrVehicleStatus;
use App\Models\Taxpayer;
use App\Services\TRA\ServiceRequest;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Traits\CustomAlert;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class UploadSaleAgreementModal extends Component
{

    use CustomAlert,WithFileUploads;


    public string $request_id;
    /**
     * @var  TemporaryUploadedFile
     */
    public $agreement;
    private ?string $agreement_path = null;


    public function mount($request_id)
    {
        $this->request_id = $request_id;
    }

    protected function rules()
    {
        return [
            'agreement'=>'required|mimes:pdf|max:1024|max_file_name_length:100'
        ];
    }


    public function submit()
    {
        $this->validate();
        DB::beginTransaction();
        try{
            $path = "MVR-Sale-Agreement-{$this->request_id}-".date('YmdHis').'-'.random_int(10000,99999).'.'.$this->agreement->extension();
            $this->agreement_path = $this->agreement->store( $path,'local');
            MvrOwnershipTransfer::query()->find($this->request_id)->update([
                'agreement_contract_path'=>$this->agreement_path,
                'mvr_request_status_id'=>MvrRequestStatus::query()->firstOrCreate(['name'=>MvrRequestStatus::STATUS_RC_PENDING_APPROVAL])->id,
            ]);
            DB::commit();
            $this->customAlert('success', 'Document Uploaded');
            return redirect()->route('mvr.transfer-ownership.show',encrypt($this->request_id));
        }catch(Exception $e){
            if (Storage::disk('local')->exists($this->agreement_path)) Storage::disk('local')->delete($this->agreement_path);
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.mvr.sale-agreement-upload-modal');
    }
}
