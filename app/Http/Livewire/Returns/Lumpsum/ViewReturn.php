<?php

namespace App\Http\Livewire\Returns\LumpSum;

use Livewire\Component;

class ViewReturn extends Component
{
    public $return;
    
    public function mount($return)
    {
        $this->return  = $return;
    }

    public function render()
    {
        return view('livewire.returns.lump-sum.view-return');
    }
}
