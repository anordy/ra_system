<?php

namespace App\Http\Livewire\Business;

use App\Models\Business;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RegistrationAddModal extends Component
{

    use LivewireAlert;

    public $name;
    public $tin;


    protected function rules()
    {
        return [
            'name' => 'required|unique:businesses',
            'tin' => 'required|unique:businesses',
        ];
    }


    public function submit()
    {
        $this->validate();
        try{
            Business::create([
                'name' => $this->name,
                'tin' => $this->tin,
            ]);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        }catch(Exception $e){
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.business.registration-add-modal');
    }
}
