<?php

namespace App\Http\Livewire\Relief;

use App\Models\Relief\ReliefProjectList;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class ReliefProjectListAddModal extends Component
{

    use LivewireAlert, WithFileUploads;

    public $name;
    public $description;
    public $government_notice_path;
    public $rate;
    public $project_id;

    public function mount($id)
    {
        $this->project_id = $id;
    }

    protected function rules()
    {
        return [
            'name' => 'required',
            'description' => 'nullable',
            'government_notice_path' => 'nullable|mimes:pdf',
        ];
    }


    public function submit()
    {
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
                'government_notice_path' => $government_notice_path ?? null,
                'created_by' => auth()->user()->id
            ]);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.relief.project_list.add-modal');
    }
}
