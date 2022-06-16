<?php

namespace App\Http\Livewire;

use Livewire\Component;

class TestModal extends Component
{
    public $name;

    public $rules = [
        'name' => 'required'
    ];

    public function render()
    {
        return view('livewire.test-modal');
    }

    public function submit(){
        $this->validate();
        dd('here');
    }

    public function hideModal(){
        $this->reset();
        $this->emit('hideModal');
    }
}
