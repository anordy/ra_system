<?php

namespace App\Http\Livewire\ReportRegister\Incident;

use App\Enum\CustomMessage;
use App\Enum\ReportRegister\RgAuditEvent;
use App\Enum\ReportRegister\RgRegisterType;
use App\Enum\ReportRegister\RgRequestorType;
use App\Enum\ReportRegister\RgStatus;
use App\Models\ReportRegister\RgAttachment;
use App\Models\ReportRegister\RgAudit;
use App\Models\ReportRegister\RgCategory;
use App\Models\ReportRegister\RgRegister;
use App\Models\ReportRegister\RgSubCategory;
use App\Traits\CustomAlert;
use App\Traits\ReportRegisterTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddIncident extends Component
{
    use CustomAlert, WithFileUploads, ReportRegisterTrait;

    public $categories = [], $categoryId, $title, $description, $files = [];
    public $subCategories = [], $subCategoryId;


    protected function rules()
    {
        return [
            'title' => 'required|max:100',
            'description' => 'required|max:255',
            'categoryId' => 'required|integer',
            'subCategoryId' => 'required|integer',
            'files.*.file' => 'nullable|mimes:pdf,xlsx,xls|max:3072|max_file_name_length:100',
            'files.*.name' => 'nullable|string|max:255',
        ];
    }

    public function mount()
    {
        $this->categories = RgCategory::query()
            ->select('id', 'name')
            ->where('requester_type', RgRequestorType::STAFF)
            ->get();
        $this->files = [['file' => null, 'name' => null]];
    }

    public function updatedcategoryId()
    {
        $this->subCategories = RgSubCategory::query()
            ->select('id', 'name')
            ->where('rg_category_id', $this->categoryId)
            ->get();
    }

    public function submit()
    {
        $this->validate();

        try {
            $attachments = [];

            foreach ($this->files ?? [] as $file) {
                if (isset($file['file'])) {
                    $filePath = $file['file']->store('report-register', 'local');
                    if ($filePath) {
                        $attachments[] = [
                            'name' => $file['name'],
                            'path' => $filePath
                        ];
                    }
                }
            }

            DB::beginTransaction();

            $rgRegister = RgRegister::create([
                'requester_type' => RgRequestorType::STAFF,
                'requester_id' => Auth::id(),
                'title' => $this->title,
                'description' => $this->description,
                'status' => RgStatus::SUBMITTED,
                'rg_category_id' => $this->categoryId,
                'rg_sub_category_id' => $this->subCategoryId,
                'register_type' => RgRegisterType::INCIDENT,
                'requester_mobile' => Auth::user()->phone
            ]);

            if (!$rgRegister) throw new Exception('Failed to save incident');

            // Add attachments
            foreach ($attachments as $fileData) {
                $attachment = RgAttachment::create([
                    'rg_register_id' => $rgRegister->id,
                    'name' => $fileData['name'],
                    'path' => $fileData['path'],
                ]);

                if (!$attachment) throw new Exception('Failed to save attachment');
            }

            // Audit incident
            $audit = RgAudit::create([
                'event' => RgAuditEvent::CREATED,
                'actor_type' => RgRequestorType::STAFF,
                'actor_id' => Auth::id(),
                'rg_register_id' => $rgRegister->id
            ]);

            if (!$audit) throw new Exception('Failed to save audit');

            DB::commit();

            $this->notifyNotifiables($this->subCategoryId, $this->title);

            $this->flash('success', 'Incident Logged Successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            foreach ($attachments ?? [] as $fileData) {
                $savedFilePath = $fileData['path'];
                if (Storage::disk('local')->exists($savedFilePath)) {
                    Storage::disk('local')->delete($savedFilePath);
                }
            }
            Log::error('REPORT-REGISTER-ADD-INCIDENT', [$e]);
            $this->customAlert('error', CustomMessage::error());
        }
    }


    public function render()
    {
        return view('livewire.report-register.incident.add');
    }

    public function addFileInput()
    {
        array_push($this->files, ['file' => '', 'name' => '']); // Add empty placeholders
    }

    public function removeFileInput($index)
    {
        unset($this->files[$index]);
    }
}
