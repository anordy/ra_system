<?php

namespace App\Http\Livewire\Business\Closure;

use Exception;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use App\Models\Business;
use Illuminate\Support\Facades\Log;
use App\Models\TemporaryBusinessClosure;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ApproveClosure extends Component
{

    use LivewireAlert;

    public $temporary_business_closures_id;
    public $temporary_business_closure;
    public $closing_date;
    public $opening_date;
    public $reason;

    public function mount()
    {
        $this->temporary_business_closure_id = (int) Route::current()->parameter('closure');
        $this->temporary_business_closure = TemporaryBusinessClosure::find($this->temporary_business_closure_id);
    }


    public function approve($status)
    {
        try{
            $this->temporary_business_closure->update([
                'approved_by' => auth()->user()->id,
                'approved_on' => $status == 'approved' ? date('Y-m-d H:i:s') : null,
                'status' => $status
            ]);
           $this->flash('success', 'Business '. $status . ' successfully');
            return redirect()->to('/business/closure');
        }catch(Exception $e){
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.business.closure.approve');
    }
}
