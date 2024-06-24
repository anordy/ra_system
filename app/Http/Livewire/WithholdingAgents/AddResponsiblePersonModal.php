<?php

namespace App\Http\Livewire\WithholdingAgents;

use App\Models\Taxpayer;
use App\Models\WaResponsiblePerson;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class AddResponsiblePersonModal extends Component
{

    use CustomAlert;

    public $responsible_person_id;
    public $title;
    public $position;
    public $responsible_persons = [];
    public $withholding_agent_id;
    public $search_triggered = false;
    public $taxpayer;
    public $reference_no;

    protected function rules()
    {
        return [
            'responsible_person_id' => 'required|strip_tag',
            'title' => 'required|strip_tag',
            'position' => 'required|strip_tag'
        ];
    }

    public function mount($id)
    {
        $this->withholding_agent_id = decrypt($id);
        $waasigned = WaResponsiblePerson::distinct()->pluck('responsible_person_id');
        $this->responsible_persons = Taxpayer::whereNotIn('id', $waasigned)->get();
    }

    public function submit()
    {
        if (!Gate::allows('withholding-agents-registration')) {
            abort(403);
        }
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
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function searchResponsiblePerson()
    {
        $this->search_triggered = true;

        $taxpayer = Taxpayer::query()->where(['reference_no' => $this->reference_no])->first();

        if (!empty($taxpayer)) {
            $this->taxpayer = $taxpayer;
        } else {
            $this->taxpayer = null;
        }

        $this->responsible_person_id = $this->taxpayer->id ?? null;
    }

    public function render()
    {
        return view('livewire.withholding-agents.add-responsible-person-modal');
    }
}
