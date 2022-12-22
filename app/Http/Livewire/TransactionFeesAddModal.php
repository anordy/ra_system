<?php

namespace App\Http\Livewire;

use App\Models\TransactionFee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class TransactionFeesAddModal extends Component
{
    use LivewireAlert;
    public $min_amount;
    public $max_amount;
    public $fee;

    protected function rules()
    {
        return [
            'min_amount' => 'required|numeric',
            'max_amount' => 'required|numeric',
            'fee'        => 'required|numeric',
        ];
    }

    public function submit()
    {
        if (!Gate::allows('setting-transaction-fees-add')) {
            abort(403);
        }

        $validate = $this->validate([
            'min_amount' => 'required|numeric',
            'max_amount' => 'required|numeric',
            'fee'        => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            $transaction_fee = new TransactionFee();
            $transaction_fee->minimum_amount = $this->min_amount;
            $transaction_fee->maximum_amount = $this->max_amount;
            $transaction_fee->fee = $this->fee;
            $transaction_fee->created_by = Auth::id();
            $transaction_fee->save();
            DB::commit();
            $this->flash('success', 'Fee added successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Throwable $exception) {
            DB::rollBack();
            dd($exception);
            Log::error($exception);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.transaction-fees-add-modal');
    }
}
