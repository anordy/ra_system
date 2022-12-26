<?php

namespace App\Http\Livewire\Mvr;


use App\Models\BusinessLocation;
use App\Models\Country;
use App\Models\MvrBodyType;
use App\Models\MvrClass;
use App\Models\MvrColor;
use App\Models\MvrDeRegistrationRequest;
use App\Models\MvrFuelType;
use App\Models\MvrMake;
use App\Models\MvrModel;
use App\Models\MvrMotorVehicle;
use App\Models\MvrMotorVehicleOwner;
use App\Models\MvrOwnershipStatus;
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

class UploadDeRegistrationInspectionReport extends Component
{

    use LivewireAlert,WithFileUploads;


    public string $request_id;
    /**
     * @var  TemporaryUploadedFile
     */
    public $inspection_report;
    private ?string $inspection_report_path = null;


    public function mount($request_id)
    {
        $this->request_id = $request_id;
    }

    protected function rules()
    {
        return [
            'inspection_report'=>'required|mimes:pdf',
            'inspection_date'=>'required|date',
        ];
    }


    public function submit()
    {
        $this->validate();
        DB::beginTransaction();
        try{
            $this->inspection_report_path = $this->inspection_report->storePubliclyAs('MVR', "De-Reg-Inspection-Report-{$this->request_id}-".date('YmdHis').'-'.random_int(10000,99999).'.'.$this->inspection_report->extension());
            MvrDeRegistrationRequest::query()->find($this->request_id)->update([
                'inspection_report_path'=>$this->inspection_report_path,
                'mvr_request_status_id'=>MvrRequestStatus::query()->firstOrCreate(['name'=>MvrRequestStatus::STATUS_RC_PENDING_APPROVAL])->id,
            ]);
            $this->flash('success', 'Inspection Report Uploaded', [], route('mvr.de-register-requests.show',encrypt($this->request_id)));
        }catch(Exception $e){
            Log::error($e);
            if (Storage::exists($this->inspection_report_path)) Storage::delete($this->inspection_report_path);
            DB::rollBack();
            $this->alert('error', 'Something went wrong, please contact our support desk for help: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.mvr.de-reg-inspection-report-upload-modal');
    }

}
