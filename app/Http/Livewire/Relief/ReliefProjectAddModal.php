<?php

namespace App\Http\Livewire\Relief;

use App\Models\Bank;
use App\Models\Relief\ReliefProject;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

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
            dd($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.relief.project.add-modal');
    }
}
