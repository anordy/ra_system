<?php

namespace App\Http\Livewire;

use App\Models\Bank;
use App\Models\EducationLevel;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class EducationLevelEditModal extends Component
{
    use LivewireAlert;

    public $name;
    public $level;

    protected function rules()
    {
        return [
            'name' => 'required|unique:education_levels,name,'.$this->level->id.',id',
        ];
    }

    public function mount($id)
    {
        $data = EducationLevel::find($id);
        $this->level = $data;
        $this->name = $data->name;
    }

    public function submit()
    {
        $this->validate();
        try {
            $this->level->update([
                'name' => $this->name,
            ]);
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }
    public function render()
    {
        return view('livewire.education-level-edit-modal');
    }
}
