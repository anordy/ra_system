<?php

namespace App\Http\Livewire;

use App\Models\Region;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RegionAddModal extends Component
{

    use LivewireAlert;

    public $name;


    protected function rules()
    {
        return [
            'name' => 'required|min:2|unique:regions',
        ];
    }


    public function submit()
    {
        if (!Gate::allows('setting-region-add')) {
            abort(403);
        }

        $this->validate();
        try{
            Region::create([
                'name' => $this->name,
            ]);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        }catch(Exception $e){
            Log::error($e);

            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.region-add-modal');
    }
}
