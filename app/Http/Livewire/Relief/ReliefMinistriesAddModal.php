<?php

namespace App\Http\Livewire\Relief;

use App\Models\Relief\ReliefMinistry;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ReliefMinistriesAddModal extends Component
{

    use LivewireAlert;

    public $name;
    public $type;
    public $description;

    public function mount()
    {
        $this->type = "Government";
    }

    protected function rules()
    {
        return [
            'name' => 'required|unique:relief_ministries,name',
            'type' => 'required',
        ];
    }


    public function submit()
    {
        $this->validate();
        try{
            ReliefMinistry::create([
                'name' => $this->name,
                'type' => $this->type,
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
        return view('livewire.relief.ministries.add-modal');
    }
}
