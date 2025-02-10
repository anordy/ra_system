<?php

namespace App\Http\Livewire\DriversLicense\Initiation;

use Livewire\Component;

class SelectType extends Component
{
    public $type;

    public function render()
    {
        return view('livewire.drivers-license.initiation.select-type');
    }
}
