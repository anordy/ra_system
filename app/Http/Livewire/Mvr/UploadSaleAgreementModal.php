<?php

namespace App\Http\Livewire\Mvr;


use App\Enum\GeneralConstant;
use App\Models\MvrOwnershipTransfer;
use App\Models\MvrRequestStatus;
use App\Traits\CustomAlert;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class UploadSaleAgreementModal extends Component
{
    use CustomAlert, WithFileUploads;
    public $request_id;
    public $agreement;
    private $agreement_path = null;


    public function mount($request_id)
    {
        $this->request_id = decrypt($request_id);
    }

    protected function rules()
    {
        return [
            'agreement' => 'required|mimes:pdf|max:1024|max_file_name_length:100|valid_pdf'
        ];
    }


    public function submit()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $path = "MVR-Sale-Agreement-{$this->request_id}-" . date('YmdHis') . '-' . random_int(10000, 99999) . '.' . $this->agreement->extension();
            $this->agreement_path = $this->agreement->store($path, 'local');
            MvrOwnershipTransfer::query()->find($this->request_id)->update([
                'agreement_contract_path' => $this->agreement_path,
                'mvr_request_status_id' => MvrRequestStatus::query()->select('id')->firstOrCreate(['name' => MvrRequestStatus::STATUS_RC_PENDING_APPROVAL])->id,
            ]);
            DB::commit();
            $this->customAlert(GeneralConstant::SUCCESS, 'Document Uploaded');
            return redirect()->route('mvr.transfer-ownership.show', encrypt($this->request_id));
        } catch (Exception $e) {
            if (Storage::disk('local')->exists($this->agreement_path)) Storage::disk('local')->delete($this->agreement_path);
            DB::rollBack();
            Log::error('UPLOAD-SALES-AGREEMENT', [$e]);
            $this->customAlert(GeneralConstant::ERROR, 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.mvr.sale-agreement-upload-modal');
    }
}
