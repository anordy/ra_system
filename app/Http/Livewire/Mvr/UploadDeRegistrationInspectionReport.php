<?php

namespace App\Http\Livewire\Mvr;


use App\Enum\GeneralConstant;
use App\Models\MvrDeRegistrationRequest;
use App\Models\MvrRequestStatus;
use App\Traits\CustomAlert;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class UploadDeRegistrationInspectionReport extends Component
{

    use CustomAlert, WithFileUploads;


    public string $request_id;
    /**
     * @var  TemporaryUploadedFile
     */
    public $inspection_report;
    private ?string $inspection_report_path = null;


    public function mount($request_id)
    {
        $this->request_id = decrypt($request_id);
    }

    protected function rules()
    {
        return [
            'inspection_report' => 'required|mimes:pdf|max:1024|max_file_name_length:100|valid_pdf',
            'inspection_date' => 'required|date',
        ];
    }


    public function submit()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $this->inspection_report_path = $this->inspection_report->storePubliclyAs('MVR', "De-Reg-Inspection-Report-{$this->request_id}-" . date('YmdHis') . '-' . random_int(10000, 99999) . '.' . $this->inspection_report->extension());
            MvrDeRegistrationRequest::query()->find($this->request_id)->update([
                'inspection_report_path' => $this->inspection_report_path,
                'mvr_request_status_id' => MvrRequestStatus::query()
                    ->select('id')
                    ->firstOrCreate(['name' => MvrRequestStatus::STATUS_RC_PENDING_APPROVAL])
                    ->id,
            ]);
            $this->flash(GeneralConstant::SUCCESS, 'Inspection Report Uploaded', [], route('mvr.de-register-requests.show', encrypt($this->request_id)));
        } catch (Exception $e) {
            if (Storage::exists($this->inspection_report_path)) Storage::delete($this->inspection_report_path);
            DB::rollBack();
            Log::error('UPDATE-DE-REGISTRATION-INSPECTION-REPORT', [$e]);
            $this->customAlert(GeneralConstant::ERROR, 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.mvr.de-reg-inspection-report-upload-modal');
    }

}
