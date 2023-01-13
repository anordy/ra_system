<?php

namespace App\Http\Livewire\Relief;

use App\Models\Relief\ReliefProject;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ReliefProjectEditModal extends Component
{

    use LivewireAlert;

    public $name;
    public $description;
    public $reliefProjectSection;

    protected function rules()
    {
        return [
            'name' => 'required|unique:relief_projects,name,'.$this->reliefProjectSection->id.',id',
            'description' => 'required',
        ];
    }

    public function mount($id)
    {
//        todo: encrypt id
        $data = ReliefProject::find(decrypt($id));
        $this->reliefProjectSection = $data;
        $this->name = $data->name;
        $this->description = $data->description;
    }

    public function submit()
    {
        if(!Gate::allows('relief-projects-edit')){
            abort(403);
        }
        $this->validate();
        try {
            $this->reliefProjectSection->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.relief.project.edit-modal');
    }
}
