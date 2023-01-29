<?php

namespace App\Http\Livewire\Relief;

use Livewire\Component;
use App\Models\Relief\Relief;

use Jantinnerezo\LivewireAlert\LivewireAlert;


class ReliefView extends Component
{
    use LivewireAlert;

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
