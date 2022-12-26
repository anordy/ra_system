<?php

namespace App\Http\Livewire\Relief;

use App\Models\Bank;
use App\Models\Relief\ReliefProject;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;

class ReliefProjectAddModal extends Component
{

    use LivewireAlert;

    public $name;
    public $description;


    protected function rules()
    {
        return [
            'name' => 'required',
            'description' => 'nullable',
        ];
    }


    public function submit()
    {
        if(!Gate::allows('relief-projects-create')){
            abort(403);
        }
        $this->validate();
        try{
            ReliefProject::create([
                'name' => $this->name,
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
        return view('livewire.relief.project.add-modal');
    }
}
