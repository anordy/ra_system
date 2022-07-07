<?php

namespace App\Http\Livewire\Business\Deregister;

use Exception;
use Livewire\Component;
use App\Models\Business;
use App\Models\BusinessStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Models\BusinessDeregistration;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class DeregisterApprove extends Component
{

    use LivewireAlert;

    public $deregister;
    public $business_id;
    public $business;


    protected function rules()
    {
        return [
            'name' => 'required|unique:banks,name',
        ];
    }

    public function mount()
    {
        $this->deregister = BusinessDeregistration::find((int) Route::current()->parameter('id'));
        $this->business = Business::find($this->deregister->business_id);
    }


    public function approve()
    {
		DB::beginTransaction();
        try{
            $this->deregister->update([
                    'approved_by' => auth()->user()->id,
                    'approved_on' => date('Y-m-d H:i:s'),
                    'status' => BusinessStatus::APPROVED
            ]);
            $this->business->update(['status' => BusinessStatus::DEREGISTERED]);
            DB::commit();
            $this->flash('success', 'De-registration approved', [], redirect()->back()->getTargetUrl());
        }catch(Exception $e){
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function reject()
    {
		DB::beginTransaction();
        try{
            $this->deregister->update([
                    'rejected_by' => auth()->user()->id,
                    'rejected_on' => date('Y-m-d H:i:s'),
                    'status' => BusinessStatus::REJECTED
            ]);
            DB::commit();
            $this->flash('success', 'De-registeration rejected', [], redirect()->back()->getTargetUrl());
        }catch(Exception $e){
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.business.deregister.deregister-approve');
    }
}
