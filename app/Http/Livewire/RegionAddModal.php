<?php

namespace App\Http\Livewire;

use App\Models\Region;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RegionAddModal extends Component
{

    use LivewireAlert;

    public $name, $location;


    protected function rules()
    {
        return [
            'name' => 'required|min:2|unique:regions',
            'location' => 'required',
        ];
    }


    public function submit()
    {
        $this->validate();
        try{
            Region::create([
                'name' => $this->name,
                'location' => $this->location
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
