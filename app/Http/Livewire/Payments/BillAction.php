<?php

namespace App\Http\Livewire\Payments;

use Exception;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use App\Traits\CustomAlert;
use App\Models\ZmBill;
use App\Models\ZmBillChange;
use App\Services\ZanMalipo\GepgResponse;
use App\Traits\PaymentsTrait;

class BillAction extends Component
{
    use CustomAlert, PaymentsTrait, GepgResponse;
    public $today;
    public $bill, $bill_change, $control_number, $cancellation_reason, $new_expiration_date, $action;

    protected $listeners = [
        'submit'
    ];

    protected $rules = [
        'action' => 'required|strip_tag',
        'control_number' => 'required|numeric',
        'cancellation_reason' => 'exclude_if:action,update|required_if:action,cancel|strip_tag',
        'new_expiration_date' => 'exclude_if:action,cancel|required_if:action,update|strip_tag'
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

        $this->customAlert('warning', "Are you sure you want to {$this->action} bill with control number {$this->control_number} ?", [
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

    public function refresh()
    {
        // $this->bill = ZmBill::where('control_number', $this->control_number)->latest()->first();
        $this->bill_change = ZmBillChange::where('zm_bill_id', $this->bill->id)->latest()->firstOrFail();
    }


    public function submit()
    {
        $this->bill = ZmBill::where('control_number', $this->control_number)->latest()->first();

        if (!$this->bill) {
            $this->customAlert('error', 'Control number not found');
            return true;
        }

        if ($this->action == 'cancel') {
            try {
                $this->cancelBill($this->bill, $this->cancellation_reason);
                $this->refresh();
                $this->customAlert('success', 'Bill cancellation request has been sent');
            } catch (Exception $e) {
                Log::error($e);
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            }
        } else if ($this->action == 'update') {
            try {
                $this->new_expiration_date = Carbon::create($this->new_expiration_date);
                $this->new_expiration_date->endOfDay();
                $this->updateBill($this->bill, $this->new_expiration_date);
                $this->refresh();
                $this->customAlert('success', 'Bill update request has been sent');
            } catch (Exception $e) {
                Log::error($e);
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
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
