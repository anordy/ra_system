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
            'max_amount' => 'sometimes|nullable|numeric',
            'fee'        => 'required|numeric',
        ];
    }

    public function submit()
    {
        $this->validate();

        if (!Gate::allows('setting-transaction-fees-add')) {
            abort(403);
        }

        DB::beginTransaction();

        try {
            $transaction_fee = new TransactionFee();
            $transaction_fee->minimum_amount = $this->min_amount;
            $transaction_fee->maximum_amount = $this->max_amount;
            $transaction_fee->fee = $this->fee;
            $transaction_fee->created_by = Auth::id();
            $transaction_fee->save();
            DB::commit();
            $this->alert('success', 'Fee added successfully');
            return redirect()->route('settings.transaction-fees.index');
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            $this->alert('error', 'Something went wrong');
            return redirect()->route('settings.transaction-fees.index');
        }
    }

    public function render()
    {
        return view('livewire.transaction-fees-add-modal');
    }
}
