<?php

namespace App\Http\Livewire\Payments;

use Exception;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\ZmBill;
use App\Services\ZanMalipo\GepgResponse;
use App\Traits\PaymentsTrait;

class BillAction extends Component
{
    use LivewireAlert, PaymentsTrait, GepgResponse;
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

    public function refresh() {
        $this->bill = ZmBill::where('control_number', $this->control_number)->latest()->first();
    }


    public function submit()
    {
        $this->bill = ZmBill::where('control_number', $this->control_number)->latest()->first();

        if (!$this->bill) {
            $this->alert('error', 'Control number not found');
            return true;
        }

        if ($this->action == 'cancel') {
            try {
                $this->cancelBill($this->bill, $this->cancellation_reason);
                $this->refresh();
                session()->flash('success', "{$this->getGepgStatus($this->bill->zan_trx_sts_code)}");
                $this->alert('success', 'Bill cancellation request has been sent');
            } catch (Exception $e) {
                Log::error($e);
                $this->refresh();
                session()->flash('error', "{$this->getGepgStatus($this->bill->zan_trx_sts_code)}");
                $this->alert('error', 'Something went wrong');
            }
        } else if ($this->action == 'update') {
            try {
               $this->updateBill($this->bill, $this->new_expiration_date);
               $this->refresh();
               session()->flash('success', "{$this->getGepgStatus($this->bill->zan_trx_sts_code)}");
               $this->alert('success', 'Bill update request has been sent');
            } catch (Exception $e) {
                Log::error($e);
                $this->refresh();
                session()->flash('error', "{$this->getGepgStatus($this->bill->zan_trx_sts_code)}");
                $this->alert('error', 'Something went wrong');
            }
        }
    }

    public function getGepgStatus($code)
    {
        return $this->getResponseCodeStatus($code)['message'];
    }


    public function render()
    {
        return view('livewire.payments.bill-action');
    }
}
