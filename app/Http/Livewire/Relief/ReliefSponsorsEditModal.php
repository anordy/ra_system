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
            'name' => 'required|unique:relief_ministries,name,'.$this->reliefSponsor->id.',id',
            'acronym' => 'required',
        ];
    }

    public function mount($id)
    {
        $data = ReliefSponsor::find($id);
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
            $this->alert('error', 'Something went wrong, Could you please contact our administrator for assistance?');
        }
    }

    public function render()
    {
        return view('livewire.relief.relief-sponsors-edit-modal');
    }
}
