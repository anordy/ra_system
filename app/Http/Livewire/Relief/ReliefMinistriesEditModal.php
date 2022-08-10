<?php

namespace App\Http\Livewire\Relief;

use App\Models\Relief\ReliefMinistry;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ReliefMinistriesEditModal extends Component
{

    use LivewireAlert;

    public $name;
    public $description;
    public $reliefProjectSection;

    protected function rules()
    {
        return [
            'name' => 'required|unique:relief_projects,name,'.$this->reliefProjectSection->id.',id',
        ];
    }

    public function mount($id)
    {
        $data = ReliefMinistry::find($id);
        $this->reliefProjectSection = $data;
        $this->name = $data->name;
        $this->description = $data->description;
    }

    public function submit()
    {
        $this->validate();
        try {
            $this->reliefProjectSection->update([
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
        return view('livewire.relief.ministries.edit-modal');
    }
}
