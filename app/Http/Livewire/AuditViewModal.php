<?php

namespace App\Http\Livewire;

use App\Models\Audit;
use Livewire\Component;
use App\Traits\CustomAlert;

class AuditViewModal extends Component
{

    use CustomAlert;

    public $old_values;
    public $new_values;
    public $audit;

    public function mount($id)
    {
        $this->audit = Audit::find(decrypt($id));
        if(is_null($this->audit)){
            abort(404);
        }
        $this->old_values = $this->audit->old_values;
        $this->new_values = $this->audit->new_values;
    }


    public function render()
    {
        return view('livewire.audit-view-modal');
    }
}
