<?php

namespace App\Http\Livewire\Returns\LumpSum;

use Livewire\Component;

class ViewReturn extends Component
{
    public $return;
    public $penalties;
    
    public function mount($return)
    {
        $this->return    = $return;
        $penalties = $return->penalties->concat($return->tax_return->penalties);
        $this->penalties =$penalties->toArray();
    }

    public function render()
    {
        return view('livewire.returns.lump-sum.view-return');
    }
}
