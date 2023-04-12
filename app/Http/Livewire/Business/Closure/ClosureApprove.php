<?php

namespace App\Http\Livewire\Business\Closure;

use Livewire\Component;
use App\Models\Business;
use App\Models\BusinessTempClosure;
use Illuminate\Support\Facades\Route;
use App\Traits\CustomAlert;

class ClosureApprove extends Component
{

    use CustomAlert;

    public $temp_closure;
    public $business_id;
    public $business;

    public function mount()
    {
        $this->temp_closure = BusinessTempClosure::find((int) decrypt(Route::current()->parameter('id')));
        if(is_null($this->temp_closure)){
            abort(404);
        }
        $this->business = Business::find($this->temp_closure->business_id);
        if(is_null($this->business)){
            abort(404);
        }
    }

    public function render()
    {
        return view('livewire.business.closure.closure-approve');
    }
}
