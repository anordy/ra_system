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
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class UploadSaleAgreementModal extends Component
{

    use LivewireAlert,WithFileUploads;


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
            'agreement'=>'required|mimes:pdf'
        ];
    }


    public function submit()
    {
        $this->validate();
        DB::beginTransaction();
        try{
            $this->agreement_path = $this->agreement->storePubliclyAs('MVR', "Sale-Agreement-{$this->request_id}-".date('YmdHis').'-'.random_int(10000,99999).'.'.$this->agreement->extension());
            MvrOwnershipTransfer::query()->find($this->request_id)->update([
                'agreement_contract_path'=>$this->agreement_path,
                'mvr_request_status_id'=>MvrRequestStatus::query()->firstOrCreate(['name'=>MvrRequestStatus::STATUS_RC_PENDING_APPROVAL])->id,
            ]);
            DB::commit();
            $this->flash('success', 'Document Uploaded', [], route('mvr.transfer-ownership.show',encrypt($this->request_id)));
        }catch(Exception $e){
            Log::error($e);
            if (Storage::exists($this->agreement_path)) Storage::delete($this->agreement_path);
            DB::rollBack();
            $this->alert('error', 'Something went wrong: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.mvr.sale-agreement-upload-modal');
    }
}
