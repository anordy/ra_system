<?php

namespace App\Http\Livewire\Relief;

use App\Models\Relief\ReliefMinistry;
use App\Models\Relief\ReliefProjectList;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;

class ReliefProjectListEditModal extends Component
{

    use LivewireAlert;

    public $name;
    public $description;
    public $rate;
    public $project;
    public $ministry_id;
    public $ministries = [];

    protected function rules()
    {
        return [
            'name' => 'required|unique:relief_project_lists,name,'.$this->project->id.',id',
            'description' => 'required',
            'rate' => 'required|numeric|min:0|max:100',
        ];
    }

    public function mount($id)
    {
        $this->ministries = ReliefMinistry::all();
        $data = ReliefProjectList::find($id);
        $this->project = $data;
        $this->name = $data->name;
        $this->description = $data->description;
        $this->rate = $data->rate;
        $this->ministry_id = $data->ministry_id;
    }

    public function submit()
    {
        if(!Gate::allows('relief-project-edit-create')){
            abort(403);
        }
        $this->validate();
        try {
            $this->project->update([
                'name' => $this->name,
                'description' => $this->description,
                'rate' => $this->rate,
                'ministry_id' => $this->ministry_id,
            ]);
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.relief.project_list.edit-modal');
    }
}
