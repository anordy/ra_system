<?php

namespace App\Http\Livewire\Payments\Actions;

use App\Jobs\Bill\UpdateBill as UpdateBillJob;
use Exception;
use Carbon\Carbon;
use App\Models\ZmBill;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class UpdateBill extends Component
{
    use LivewireAlert;

    public $bill, $current_expiration_date, $new_expiration_date, $extension_reason;

    protected $rules = [
        'new_expiration_date' => 'required'
    ];

    public function mount($id)
    {
        $this->bill = ZmBill::findOrFail($id);
        $this->current_expiration_date = Carbon::create($this->bill->expire_date)->format('Y-m-d');
    }

    public function submit()
    {
        if (!Gate::allows('manage-payments-edit')) {
            abort(403);
        }

        $this->validate();

        try {
            $now = Carbon::now();
            UpdateBillJob::dispatch($this->bill, $this->new_expiration_date)->delay($now->addSeconds(2));
            $this->flash('success', 'Bill expiration date extension request has been sent!', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }


    public function render()
    {
        return view('livewire.payments.update-bill-modal');
    }
}
