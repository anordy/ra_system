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
use App\Traits\CustomAlert;
use Livewire\Component;

class ZrbBankAccountAddModal extends Component
{
    use CustomAlert, DualControlActivityTrait;
    public $account_name;
    public $bank_id;
    public $account_number;
    public $branch_name;
    public $swift_code;
    public $currency;
    public $banks;
    public $currencies;
    public $is_transfer;

    protected $rules = [
        'account_name' => 'required|strip_tag',
        'branch_name' => 'required|strip_tag',
        'swift_code' => 'required|strip_tag',
        'is_transfer' => 'required',
        'account_number' => 'required|numeric|digits_between:9,20',
        'currency' => 'required|strip_tag',
    ];

    protected $messages = [
        'bank_id' => 'Bank is required',
        'account_name.required' => 'Account name is required.',
        'branch_name.required' => 'Branch name is required.',
        'swift_code.required' => 'Swift code is required.',
        'is_transfer.required' => 'Bank account type is required.',
        'account_number.required' => 'Account number is required.',
        'currency.required' => 'currency is required.',
    ];

    public function mount(){
        $this->currencies = Currency::select('id', 'iso')->get();
        $this->banks = Bank::select('id', 'name')->get();
    }

    public function submit()
    {
        
        if (!Gate::allows('zrb-bank-account-add')) {
            abort(403);
        }
        $this->validate();
        $currency = json_decode($this->currency);
        DB::beginTransaction();
        try {
            $zrbBankAccount = ZrbBankAccount::create([
                'bank_id' => $this->bank_id,
                'account_name' => $this->account_name,
                'branch_name' => $this->branch_name,
                'swift_code' => $this->swift_code,
                'is_transfer' => $this->is_transfer,
                'account_number' => $this->account_number,
                'currency_id' => $currency->id,
                'currency_iso' => $currency->iso,
                'created_at' => Carbon::now()
            ]);
            
            $this->triggerDualControl(get_class($zrbBankAccount), $zrbBankAccount->id, DualControl::ADD, 'adding ZRA bank account');
            DB::commit();
            $this->customAlert('success', 'Record added successfully');
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact our administrator for assistance?');
        }
    }

    public function render()
    {
        return view('livewire.settings.zrb-banks.zrb-bank-account-add-modal');
    }
}
