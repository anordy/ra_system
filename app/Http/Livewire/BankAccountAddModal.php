<?php

namespace App\Http\Livewire;

use App\Enum\Currencies;
use App\Models\Bank;
use App\Models\BankAccount;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Illuminate\Validation\Rule;
use Livewire\Component;

class BankAccountAddModal extends Component
{
    use CustomAlert;

    public $name;
    public $banks, $bank;
    public $account_number;
    public $currency;

    protected function rules()
    {
        return [
            'name' => ['required', 'strip_tag', Rule::unique('bank_accounts', 'account_name')->whereNull('deleted_at')],
            'account_number' => ['required', 'strip_tag', Rule::unique('bank_accounts', 'account_number')->whereNull('deleted_at')],
            'bank' => ['required', 'string'],
            'currency' => ['required', 'string', Rule::in(Currencies::getConstants())],
        ];
    }

    public function mount(){
        $this->banks = Bank::select('name', 'id')->get();
    }


    public function submit()
    {
        if (!Gate::allows('setting-bank-add')) {
            abort(403);
        }

        $this->validate();
        
        try{
            BankAccount::create([
                'bank_id' => $this->bank,
                'account_name' => $this->name,
                'account_number' => $this->account_number,
                'currency' => $this->currency
            ]);

            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        }catch(Exception $e){
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.bank-account-add-modal');
    }
}
