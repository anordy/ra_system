<?php

namespace App\Http\Livewire;

use Exception;
use App\Models\Audit;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class AuditViewModal extends Component
{

    use LivewireAlert;

    public $old_values;
    public $new_values;
    public $event;
    public $created_at;
    public $fname;
    public $lname;
    public $tags;

    public function mount($id)
    {
        try {
            // TODO ON QUERY: Join with users table and get fname & lname
            $audit = Audit::find($id);
            $this->old_values = $audit->old_values;
            $this->new_values = $audit->new_values;
            $this->event = $audit->event;
            $this->created_at = $audit->created_at;
            $this->tags = $audit->tags;
            // dd(json_decode($this->old_values)->name);
            // $this->fname = $audit->fname;
            // $this->lname = $audit->lname;
        } catch (Exception $e) {
            Log::error($e);
        }
     
    }


    public function render()
    {
        return view('livewire.audit-view-modal');
    }
}
