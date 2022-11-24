<?php

namespace App\Http\Livewire\Business\Deregister;

use Livewire\Component;
use App\Models\Business;
use Illuminate\Support\Facades\Route;
use App\Models\BusinessDeregistration;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class DeregisterApprove extends Component
{

    use LivewireAlert;

    public $deregister;
    public $business_id;
    public $business;

    public function mount()
    {
        $this->deregister = BusinessDeregistration::find((int) decrypt(Route::current()->parameter('id')));
        $this->business = Business::find($this->deregister->business_id);
    }

    public function render()
    {
        return view('livewire.business.deregister.deregister-approve');
    }
}
