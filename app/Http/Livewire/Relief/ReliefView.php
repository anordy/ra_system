<?php

namespace App\Http\Livewire\Relief;

use Livewire\Component;
use App\Models\Relief\Relief;

use App\Traits\CustomAlert;


class ReliefView extends Component
{
    use CustomAlert;

    public $relief;

    public function mount($enc_id)
    {
        $this->relief = Relief::find(decrypt($enc_id));
        if (is_null($this->relief)) {
            abort(404);
        }
    }
    
    public function render()
    {
        return view('livewire.relief.relief-view');
    }
}
