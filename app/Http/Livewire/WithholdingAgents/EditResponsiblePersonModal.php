<?php

namespace App\Http\Livewire\WithholdingAgents;

use Exception;
use Livewire\Component;
use App\Models\Taxpayer;
use App\Models\WaResponsiblePerson;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class EditResponsiblePersonModal extends Component
{

    use LivewireAlert;

    public $responsible_person_id;
    public $title;
    public $position;
    public $responsible_persons = [];
    public $wa_responsible_person_id;
    public $wa_responsible_person;

    protected function rules()
    {
        return [
            'responsible_person_id' => 'required',
        ];
    }

    public function mount($id)
    {   
        $this->wa_responsible_person = WaResponsiblePerson::find($id);
        $this->title = $this->wa_responsible_person->title;
        $this->position = $this->wa_responsible_person->position;
        $this->responsible_person_id = $this->wa_responsible_person->responsible_person_id;
        $this->responsible_persons = Taxpayer::select('id', 'first_name', 'middle_name', 'last_name')->get();
    }

    public function submit()
    {
        $this->validate();
        try {
            $this->wa_responsible_person->update([
                'title' => $this->title,
                'position' => $this->position,
            ]);
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.withholding-agents.edit-responsible-person-modal');
    }
}
