<?php

namespace App\Http\Livewire\TransferForm;

use App\Models\ZrbBankAccount;
use Livewire\Component;

class TransferFormGenerator extends Component
{
    public $zrbBankAccounts;
    public $billType;
    public $bankAccountId;

    public function mount($currency, $billId) {
        $this->zrbBankAccounts = ZrbBankAccount::select('id', 'bank_id')->with('bank')->where('currency_iso', $currency)->where('is_approved', 1)->get();   
        $this->billId = $billId;
    }

    protected $rules = [
        'bankAccountId' => 'required',
    ];

    protected $messages = [
        'bankAccountId' => 'Bank is required',
    ];

    public function submit(){
        $this->validate();
        return redirect()->route('bill.transfer', ['billId' => encrypt($this->billId), 'bankAccountId' => encrypt($this->bankAccountId)]);
    }

    public function render()
    {
        return view('livewire.transfer-form.transfer-form-generator');
    }
}
