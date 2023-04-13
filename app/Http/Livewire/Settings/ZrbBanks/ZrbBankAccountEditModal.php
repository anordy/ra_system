<?php

namespace App\Http\Livewire\Settings\ZrbBanks;

use App\Models\Bank;
use App\Models\Currency;
use App\Models\DualControl;
use App\Models\ZrbBankAccount;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class ZrbBankAccountEditModal extends Component
{

    use CustomAlert, DualControlActivityTrait;
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
    public $old_values;
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

    public function mount($id){
        $this->zrbBankAccount = ZrbBankAccount::findOrFail(decrypt($id));
        $this->bank_id = $this->zrbBankAccount->bank_id;
        $this->account_name = $this->zrbBankAccount->account_name;
        $this->branch_name = $this->zrbBankAccount->branch_name;
        $this->swift_code = $this->zrbBankAccount->swift_code;
        $this->account_number = $this->zrbBankAccount->account_number;
        $this->is_transfer = $this->zrbBankAccount->is_transfer;

        $this->old_values = [
            'bank_id' => $this->bank_id,
            'account_name' => $this->account_name,
            'branch_name' => $this->branch_name,
            'swift_code' => $this->swift_code,
            'is_transfer' => $this->is_transfer,
            'account_number' => $this->account_number,
            'currency_id' => $this->zrbBankAccount->id,
            'currency_iso' => $this->zrbBankAccount->currency_iso,
        ];

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
            $payload = [
                'bank_id' => $this->bank_id,
                'account_name' => $this->account_name,
                'branch_name' => $this->branch_name,
                'swift_code' => $this->swift_code,
                'is_transfer' => $this->is_transfer,
                'account_number' => $this->account_number,
                'currency_id' => $currency->id,
                'currency_iso' => $currency->iso,
            ];
            $this->triggerDualControl(get_class($this->zrbBankAccount), $this->zrbBankAccount->id, DualControl::EDIT, 'editing ZRA bank account', json_encode($this->old_values), json_encode($payload));
            DB::commit();
            $this->customAlert('success', DualControl::SUCCESS_MESSAGE,  ['timer'=>10000]);
            $this->flash('success', DualControl::SUCCESS_MESSAGE, [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact our administrator for assistance?');
        }
    }

    public function render()
    {
        return view('livewire.settings.zrb-banks.zrb-bank-account-edit-modal');
    }
}
