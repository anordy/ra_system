<?php

namespace App\Http\Livewire\Relief;

use App\Models\Relief\ReliefMinistry;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;

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
        if(!Gate::allows('relief-ministries-create')){
            abort(403); 
        }
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
            $this->alert('error', 'Something went wrong, Could you please contact our administrator for assistance?');
        }
    }

    public function render()
    {
        return view('livewire.relief.ministries.add-modal');
    }
}
