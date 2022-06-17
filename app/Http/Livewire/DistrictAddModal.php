<?php

namespace App\Http\Livewire;

use App\Models\Region;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DistrictAddModal extends Component
{

    use LivewireAlert;

    public $name;
    public $region_id;


    protected function rules()
    {
        return [
            'region_id' => 'required|exists:regions,id',
            'name' => 'required|min:2|unique:regions',
        ];
    }


    public function submit()
    {
        $this->validate();
        try{
            Region::create([
                'name' => $this->name,
                'region_id' => $this->region_id,
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
