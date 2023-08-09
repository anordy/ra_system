<?php

namespace App\Http\Livewire\TransferForm;

use App\Models\ZmBill;
use App\Models\ZrbBankAccount;
use Livewire\Component;

class TransferFormGenerator extends Component
{
    public $zrbBankAccounts;
    public $billType;
    public $billId;
    public $bankAccountId;
    public $useSavedBankAccounts;
    public $businessBankAccId;
    public $businessBanks;

    public function mount($currency, $billId) {
        $this->zrbBankAccounts = ZrbBankAccount::select('id', 'bank_id')->with('bank')->where('currency_iso', $currency)->where('is_approved', 1)->where('is_transfer', 1)->get();
        $this->billId = decrypt($billId);
        $bill = ZmBill::findOrFail($this->billId);
        $this->businessBanks = $bill->billable->business->banks;
    }

    protected $rules = [
        'bankAccountId' => 'required',
        'useSavedBankAccounts' => 'required',
        'businessBankAccId' => 'required_if:useSavedBankAccounts,1'
    ];

    protected $messages = [
        'bankAccountId' => 'Bank is required',
        'businessBankAccId.required_if' => 'Please select your business Bank Account For Transfer'
    ];

    public function submit(){
        $this->validate();
        return redirect()->route('bill.transfer', ['billId' => encrypt($this->billId), 'bankAccountId' => encrypt($this->bankAccountId), 'businessbankAccId' => encrypt($this->businessBankAccId)]);
    }
    
    public function render()
    {
        return view('livewire.transfer-form.transfer-form-generator');
    }
}
