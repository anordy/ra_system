<?php

namespace App\Http\Livewire\Relief;

use App\Models\Relief\ReliefSponsor;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ReliefSponsorsEditModal extends Component
{

    use LivewireAlert;

    public $name;
    public $acronym;
    public $description;
    public $reliefSponsor;

    protected function rules()
    {
        return [
            'name' => 'required|unique:relief_ministries,name,'.$this->reliefSponsor->id.',id|strip_tag',
            'acronym' => 'required|strip_tag',
        ];
    }

    public function mount($id)
    {
        $data = ReliefSponsor::find($id);
        if (is_null($data)){
            abort(404, 'Sponsor not found');
        }
        $this->reliefSponsor = $data;
        $this->name = $data->name;
        $this->acronym = $data->acronym;
        $this->description = $data->description;
    }

    public function submit()
    {
        if(!Gate::allows('relief-ministries-edit')){
            abort(403);
        }
        $this->validate();
        try {
            $this->reliefSponsor->update([
                'name' => $this->name,
                'acronym' => $this->acronym,
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
        return view('livewire.relief.relief-sponsors-edit-modal');
    }
}
