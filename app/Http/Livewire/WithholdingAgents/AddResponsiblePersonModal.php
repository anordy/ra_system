<?php

namespace App\Http\Livewire\WithholdingAgents;

use Exception;
use Livewire\Component;
use App\Models\Taxpayer;
use App\Models\WaResponsiblePerson;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class AddResponsiblePersonModal extends Component
{

    use LivewireAlert;

    public $responsible_person_id;
    public $title;
    public $position;
    public $responsible_persons = [];
    public $withholding_agent_id;

    protected function rules()
    {
        return [
            'responsible_person_id' => 'required',
            'title' => 'required',
            'position' => 'required'
        ];
    }

    public function mount($id)
    {   
        $this->withholding_agent_id = $id;
        $this->responsible_persons = Taxpayer::select('id', 'first_name', 'middle_name', 'last_name')->get();
    }

    public function submit()
    {
        $this->validate();
        try {
            WaResponsiblePerson::create([
                'withholding_agent_id' => $this->withholding_agent_id,
                'responsible_person_id' => $this->responsible_person_id,
                'title' => $this->title,
                'position' => $this->position,
                'officer_id' => auth()->user()->id
            ]);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.withholding-agents.add-responsible-person-modal');
    }
}
