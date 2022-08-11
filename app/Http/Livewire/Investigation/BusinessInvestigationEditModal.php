<?php

namespace App\Http\Livewire\Investigation;

use App\Models\Investigation\TaxInvestigation;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BusinessInvestigationEditModal extends Component
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
        $data = TaxInvestigation::find($id);
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
        return view('livewire.investigation.business.edit-modal');
    }
}
