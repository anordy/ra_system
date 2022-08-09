<?php

namespace App\Http\Livewire\Relief;

use App\Models\Relief\ReliefProjectList;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ReliefProjectListEditModal extends Component
{

    use LivewireAlert;

    public $name;
    public $description;
    public $rate;
    public $project;

    protected function rules()
    {
        return [
            'name' => 'required|unique:relief_project_lists,name,'.$this->project->id.',id',
            'description' => 'required',
            'rate' => 'required|numeric',
        ];
    }

    public function mount($id)
    {
        $data = ReliefProjectList::find($id);
        $this->project = $data;
        $this->name = $data->name;
        $this->description = $data->description;
        $this->rate = $data->rate;
    }

    public function submit()
    {
        $this->validate();
        try {
            $this->project->update([
                'name' => $this->name,
                'description' => $this->description,
                'rate' => $this->rate,
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
