<?php

namespace App\Http\Livewire\Business\Closure;

use Exception;
use Livewire\Component;
use App\Models\Business;
use App\Models\BusinessStatus;
use Illuminate\Support\Facades\DB;
use App\Models\BusinessTempClosure;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ClosureConfirmModal extends Component
{

    use LivewireAlert;

    public $temp_closure;
    public $business_id;
    public $business;


    protected function rules()
    {
        return [
            'name' => 'required|unique:banks,name',
        ];
    }

    public function mount($id)
    {
        $this->temp_closure = BusinessTempClosure::find($id);
        $this->business = Business::find($this->temp_closure->business_id);
    }


    public function confirm()
    {
		DB::beginTransaction();
        try{
            $this->temp_closure->update([
                    'approved_by' => auth()->user()->id,
                    'approved_on' => date('Y-m-d H:i:s'),
                    'status' => BusinessStatus::APPROVED
            ]);
            $this->business->update(['status' => BusinessStatus::TEMP_CLOSED]);
            DB::commit();
            $this->flash('success', 'Closure confirmed', [], redirect()->back()->getTargetUrl());
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
            $this->temp_closure->update([
                    'rejected_by' => auth()->user()->id,
                    'rejected_on' => date('Y-m-d H:i:s'),
                    'status' => BusinessStatus::REJECTED
            ]);
            DB::commit();
            $this->flash('success', 'Closure rejected', [], redirect()->back()->getTargetUrl());
        }catch(Exception $e){
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.business.closure.closure-confirm-modal');
    }
}
