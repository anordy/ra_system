<?php

namespace App\Http\Livewire\Payments;

use Exception;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Jobs\Bill\CancelBill as CancelBillJob;
use App\Jobs\Bill\UpdateBill as UpdateBillJob;
use App\Models\ZmBill;
use App\Traits\PaymentsTrait;

class BillAction extends Component
{
    use LivewireAlert, PaymentsTrait;
    public $today;
    public $bill, $control_number, $cancellation_reason, $new_expiration_date, $action;

    protected $listeners = [
        'submit'
    ];

    protected $rules = [
        'action' => 'required',
        'control_number' => 'required|numeric',
        'cancellation_reason' => 'exclude_if:action,update|required_if:action,cancel',
        'new_expiration_date' => 'exclude_if:action,cancel|required_if:action,update'
    ];

    public function mount()
    {
        $this->today = Carbon::today()->format('Y-m-d');
    }

    public function billAction()
    {
        $this->validate();

        if (!Gate::allows('manage-payments-edit')) {
            abort(403);
        }

        $this->alert('warning', "Are you sure you want to {$this->action} bill with control number {$this->control_number} ?", [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => ucfirst($this->action) . " bill",
            'onConfirmed' => 'submit',
            'showCancelButton' => true,
            'cancelButtonText' => 'Close',
            'timer' => null,
        ]);
    }


    public function submit()
    {


        $bill = ZmBill::where('control_number', $this->control_number)->latest()->first();

        if (!$bill) {
            $this->alert('error', 'Control number not found');
            return true;
        }


        $now = Carbon::now();

        if ($this->action == 'cancel') {
            try {
                $cancelBill = $this->cancelBill($bill, $this->cancellation_reason);
                // If response returns error exit
                $this->flash('success', 'Bill cancellation request has been sent', [], redirect()->back()->getTargetUrl());
            } catch (Exception $e) {
                Log::error($e);
                $this->alert('error', 'Something went wrong');
            }
        } else if ($this->action == 'update') {
            try {
               $this->updateBill($bill, $this->new_expiration_date);
                // dd($updateBill);
                // If response returns error exit
                // if (array_key_exists('error', $updateBill)) {
                //     $this->flash('error', 'Something went wrong');
                //     return true;
                // }
                $this->flash('success', 'Bill update request has been sent', [], redirect()->back()->getTargetUrl());
            } catch (Exception $e) {
                Log::error($e);
                $this->alert('error', 'Something went wrong');
            }
        }
    }


    public function render()
    {
        return view('livewire.payments.bill-action');
    }
}
