<?php

namespace App\Http\Livewire\Settings\ZrbBanks;

use App\Models\Bank;
use App\Models\Currency;
use App\Models\DualControl;
use App\Models\ZrbBankAccount;
use App\Traits\DualControlActivityTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ZrbBankAccountEditModal extends Component
{

    use LivewireAlert, DualControlActivityTrait;
    public $zrbBankAccount;
    public $account_name;
    public $bank_id;
    public $account_number;
    public $branch_name;
    public $swift_code;
    public $currency;
    public $currency_id;
    public $currency_iso;
    public $banks;
    public $currencies;

    protected $rules = [
        'account_name' => 'required',
        'branch_name' => 'required',
        'swift_code' => 'required',
        'account_number' => 'required|numeric|digits_between:9,20',
        'currency' => 'required',
    ];

    protected $messages = [
        'bank_id' => 'Bank is required',
        'account_name.required' => 'Account name is required.',
        'branch_name.required' => 'Branch name is required.',
        'swift_code.required' => 'Swift code is required.',
        'account_number.required' => 'Account number is required.',
        'currency.required' => 'currency is required.',
    ];

    public function mount($id){
        $this->zrbBankAccount = ZrbBankAccount::findOrFail(decrypt($id));
        $this->bank_id = $this->zrbBankAccount->bank_id;
        $this->account_name = $this->zrbBankAccount->account_name;
        $this->branch_name = $this->zrbBankAccount->branch_name;
        $this->swift_code = $this->zrbBankAccount->swift_code;
        $this->account_number = $this->zrbBankAccount->account_number;

        $this->currencies = Currency::select('id', 'iso')->get();
        $this->banks = Bank::select('id', 'name')->get();
    }

    public function submit()
    {
        
        if (!Gate::allows('zrb-bank-account-edit')) {
            abort(403);
        }
        $this->validate();
        $currency = json_decode($this->currency);
        DB::beginTransaction();
        try {
            $this->zrbBankAccount->update([
                'bank_id' => $this->bank_id,
                'account_name' => $this->account_name,
                'branch_name' => $this->branch_name,
                'swift_code' => $this->swift_code,
                'account_number' => $this->account_number,
                'currency_id' => $currency->id,
                'currency_iso' => $currency->iso,
            ]);
            $this->triggerDualControl(get_class($this->zrbBankAccount), $this->zrbBankAccount->id, DualControl::EDIT, 'edit zrb bank account');
            DB::commit();
            $this->alert('success', 'Record edited successfully');
            $this->flash('success', 'Record edited successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact our administrator for assistance?');
        }
    }

    public function render()
    {
        return view('livewire.settings.zrb-banks.zrb-bank-account-edit-modal');
    }
}
