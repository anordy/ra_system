<?php

namespace App\Http\Livewire\Business\Closure;

use Livewire\Component;
use App\Models\Business;
use App\Models\BusinessTempClosure;
use Illuminate\Support\Facades\Route;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ClosureApprove extends Component
{

    use LivewireAlert;

    public $temp_closure;
    public $business_id;
    public $business;

    public function mount()
    {
        $this->temp_closure = BusinessTempClosure::find((int) decrypt(Route::current()->parameter('id')));
        $this->business = Business::find($this->temp_closure->business_id);
    }

    public function render()
    {
        return view('livewire.business.closure.closure-approve');
    }
}
