<?php

namespace App\Http\Livewire;

use App\Models\Audit;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class AuditViewModal extends Component
{

    use LivewireAlert;

    public $old_values;
    public $new_values;
    public $audit;

    public function mount($id)
    {
        $this->audit = Audit::find($id);
        $this->old_values = $this->audit->old_values;
        $this->new_values = $this->audit->new_values;
    }


    public function render()
    {
        return view('livewire.audit-view-modal');
    }
}
