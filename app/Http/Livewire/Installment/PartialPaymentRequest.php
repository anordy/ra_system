<?php

namespace App\Http\Livewire\Installment;

use App\Models\PartialPayment;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PartialPaymentRequest extends Component
{
    public function render()
    {
        $partials = PartialPayment::with('installmentItem.installment')->get();
//        dd($partials);
        return view('livewire.installment.partial-payment-request',compact('partials'));
    }
}
