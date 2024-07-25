<?php

namespace App\Http\Livewire\Settings\CertificateSignature;

use App\Models\CertificateSignature;
use App\Models\DualControl;
use App\Traits\CustomAlert;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class CertificateSignatureAddModal extends Component
{

    use CustomAlert, DualControlActivityTrait, WithFileUploads;

    public $name, $title, $startDate, $endDate, $signature;


    protected function rules()
    {
        return [
            'title' => 'required|alpha_num_space|min:5|max:50',
            'name' => 'required|alpha_num_space|min:5|max:100',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'signature' => 'required|file|mimes:png,jpg,jpeg|max:1024',
        ];
    }

    public function mount()
    {

    }


    public function submit()
    {
        if (!Gate::allows('setting-district-add')) {
            // abort(403);
        }

        $this->validate();

        // Check for overlapping time

        DB::beginTransaction();

        try {

            $signaturePath = "";
            $base64 = null;

            if ($this->signature) {
                $signaturePath = $this->signature->store('certificate-signature', 'local');

                $file = storage_path().'/app/'. $signaturePath;

                // Get base64 image
                $type = pathinfo($file, PATHINFO_EXTENSION);
                $data = file_get_contents($file);

                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }

            $certificate = CertificateSignature::create([
                'name' => strtoupper($this->name),
                'title' => strtoupper($this->title),
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
                'signature_path' => $signaturePath,
                'image' => $base64
            ]);

            if (!$certificate) throw new Exception('Failed to save certificate');

            $this->triggerDualControl(get_class($certificate), $certificate->id, DualControl::ADD, 'adding new certificate signature of ' . $this->name . '');

            DB::commit();

            $this->customAlert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 8000]);

            return redirect()->route('settings.certificate-signature.index');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', DualControl::ERROR_MESSAGE);
        }
    }

    public function render()
    {
        return view('livewire.settings.certificate-signature.certificate-signature-add-modal');
    }
}
