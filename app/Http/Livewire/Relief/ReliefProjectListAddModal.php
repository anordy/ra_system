<?php

namespace App\Http\Livewire\Relief;

use App\Models\Relief\ReliefMinistry;
use App\Models\Relief\ReliefProjectList;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Gate;

class ReliefProjectListAddModal extends Component
{

    use LivewireAlert, WithFileUploads;

    public $name;
    public $description;
    public $government_notice_path;
    public $rate;
    public $project_id;
    public $ministry_id;
    public $ministries = [];

    public function mount($id)
    {
        $this->project_id = $id;

        $this->ministries = ReliefMinistry::all();
    }

    protected function rules()
    {
        return [
            'name' => 'required',
            'description' => 'nullable',
            'ministry_id' => 'nullable',
            'rate' => 'required|numeric|min:0|max:100',
            'government_notice_path' => 'nullable|mimes:pdf',
        ];
    }

    public function submit()
    {
        if (!Gate::allows('relief-projcts-list-create')) {
            abort(403);
        }

        $this->validate();
        $government_notice_path = null;
        if ($this->government_notice_path) {
            $government_notice_path = $this->government_notice_path->store('relief');
        }
        try {
            ReliefProjectList::create([
                'project_id' => $this->project_id,
                'name' => $this->name,
                'description' => $this->description,
                'rate' => $this->rate,
                'ministry_id' => $this->ministry_id ?? null,
                'government_notice_path' => $government_notice_path ?? null,
                'created_by' => auth()->user()->id,
            ]);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.relief.project_list.add-modal');
    }
}
