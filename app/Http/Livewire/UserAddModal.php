<?php

namespace App\Http\Livewire;

use Livewire\Component;

class UserAddModal extends Component
{
    public function render()
    {
        return view('livewire.user-add-modal');
    }


    public function submit(){
        dd('here');
    }

    public function hideModal(){
        $this->reset();
        $this->emit('hideModal');
    }
}
