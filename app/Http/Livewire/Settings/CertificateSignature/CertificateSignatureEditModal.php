<?php

namespace App\Http\Livewire\Settings\CertificateSignature;

use App\Models\CertificateSignature;
use App\Models\DualControl;
use App\Traits\CustomAlert;
use App\Traits\DualControlActivityTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class CertificateSignatureEditModal extends Component
{

    use CustomAlert, DualControlActivityTrait, WithFileUploads;

    public $name, $title, $startDate, $endDate, $signature, $certificate, $currentSignature, $oldValues;


    protected function rules()
    {
        $rules = [
            'title' => 'required|alpha_num_space|min:5|max:50',
            'name' => 'required|alpha_num_space|min:5|max:100',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after:startDate',
        ];

        if (!$this->signature) {
            $rules['signature'] = 'required|file|mimes:png,jpg,jpeg|max:1024';
        }

        return $rules;
    }

    public function mount($id)
    {
        $this->certificate = CertificateSignature::findOrFail(decrypt($id), ['id', 'name', 'title', 'start_date', 'end_date', 'signature_path', 'is_updated', 'is_approved', 'image']);
        $this->name = $this->certificate->name;
        $this->title = $this->certificate->title;
        $this->signature = $this->certificate->signature_path;
        $this->currentSignature = $this->certificate->image;
        $this->startDate = $this->certificate->start_date ? Carbon::create($this->certificate->start_date)->format('Y-m-d') : now()->format('Y-m-d');
        $this->endDate = $this->certificate->end_date ? Carbon::create($this->certificate->end_date)->format('Y-m-d') : now()->format('Y-m-d');
        $this->oldValues = [
            'name' => $this->certificate->name,
            'title' => $this->certificate->title,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ];
    }


    public function submit()
    {
        if (!Gate::allows('setting-certificate-signature-edit')) {
             abort(403);
        }

        $this->validate();

        DB::beginTransaction();

        try {

            if ($this->signature != $this->certificate->signature_path) {
                $signaturePath = $this->signature->store('certificate-signature', 'local');

                $file = Storage::disk('local')->get($signaturePath);

                // Get base64 image
                $type = pathinfo($signaturePath, PATHINFO_EXTENSION);

                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($file);
                $this->certificate->image = $base64;
                $this->certificate->signature_path = $signaturePath;
            }

            $this->certificate->name = strtoupper($this->name);
            $this->certificate->title = strtoupper($this->title);
            $this->certificate->start_date = $this->startDate;
            $this->certificate->end_date = $this->endDate;

            $newValues = [
                'name' => $this->certificate->name,
                'title' => $this->certificate->title,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
            ];

            if (!$this->certificate->save()) throw new Exception('Failed to update certificate');

            $this->triggerDualControl(get_class($this->certificate), $this->certificate->id, DualControl::EDIT, 'Editing certificate signature of ' . $this->name . '', json_encode($this->oldValues), json_encode($newValues));

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
        return view('livewire.settings.certificate-signature.certificate-signature-edit-modal');
    }
}
