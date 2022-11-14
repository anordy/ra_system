<?php

namespace App\Http\Livewire\Payments\Actions;

use Exception;
use Carbon\Carbon;
use App\Models\ZmBill;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Jobs\Bill\CancelBill as CancelBillJob;

class CancelBill extends Component
{
    use LivewireAlert;

    public $bill, $cancellation_reason;

    protected $rules = [
        'cancellation_reason' => 'required'
    ];

    public function mount($id)
    {
        $this->bill = ZmBill::findOrFail($id);
    }

    public function submit()
    {
        if (!Gate::allows('manage-payments-edit')) {
            abort(403);
        }

        $this->validate();

        try {
            $now = Carbon::now();
            CancelBillJob::dispatch($this->bill, $this->cancellation_reason)->delay($now->addSeconds(2));
            $this->flash('success', 'Bill cancellation request has been sent', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }


    public function render()
    {
        return view('livewire.payments.cancel-bill-modal');
    }
}
