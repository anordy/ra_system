<?php

namespace App\Http\Livewire\Relief;

use App\Models\Relief\ReliefSponsor;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ReliefSponsorAddModal extends Component
{
    use LivewireAlert;

    public $name;
    public $acronym;
    public $description;

    protected function rules()
    {
        return [
            'name' => 'required|unique:relief_sponsors,name',
            'acronym' => 'required|unique:relief_sponsors,name',
        ];
    }


    public function submit()
    {
        if(!Gate::allows('relief-sponsors-create')){
            abort(403); 
        }
        $this->validate();
        try{
            ReliefSponsor::create([
                'name' => $this->name,
                'acronym' => $this->acronym,
                'description' => $this->description,
                'created_by' => auth()->user()->id
            ]);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        }catch(Exception $e){
            Log::error($e);
            $this->alert('error', 'Something went wrong, Please contact our support desk for help');
        }
    }

    public function render()
    {
        return view('livewire.relief.relief-sponsor-add-modal');
    }
}
