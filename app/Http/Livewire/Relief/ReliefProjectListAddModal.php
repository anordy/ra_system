<?php

namespace App\Http\Livewire\Relief;

use App\Models\Relief\ReliefMinistry;
use App\Models\Relief\ReliefProjectList;
use App\Models\Relief\ReliefSponsor;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class ReliefProjectListAddModal extends Component
{

    use CustomAlert, WithFileUploads;

    public $name;
    public $description;
    public $government_notice_path;
    public $rate;
    public $project_id;
    public $ministry_id;
    public $relief_sponsor_id;
    public $ministries = [];
    public $sponsors = [];

    public function mount($id)
    {
        $this->project_id = decrypt($id);

        $this->ministries = ReliefMinistry::all();
        $this->sponsors = ReliefSponsor::all();
    }

    protected function rules()
    {
        return [
            'name' => 'required|strip_tag',
            'description' => 'nullable|strip_tag',
            'ministry_id' => 'nullable|strip_tag',
            'relief_sponsor_id' => 'nullable|strip_tag',
            'rate' => 'required|numeric|min:0|max:100|strip_tag',
            'government_notice_path' => 'nullable|mimes:pdf|max:1024|max_file_name_length:100',
        ];
    }

    public function submit()
    {
        if (!Gate::allows('relief-projects-list-create')) {
            abort(403);
        }

        $this->validate();
        $government_notice_path = null;
        if ($this->government_notice_path) {
            $government_notice_path = $this->government_notice_path->store('relief', 'local');
        }

        try {
            ReliefProjectList::create([
                'project_id' => $this->project_id,
                'name' => $this->name,
                'description' => $this->description,
                'rate' => $this->rate,
                'ministry_id' => $this->ministry_id ?? null,
                'relief_sponsor_id' => $this->relief_sponsor_id ?? null,
                'government_notice_path' => $government_notice_path ?? null, // todo: to confirm
                'created_by' => auth()->user()->id,
            ]);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.relief.project_list.add-modal');
    }
}
