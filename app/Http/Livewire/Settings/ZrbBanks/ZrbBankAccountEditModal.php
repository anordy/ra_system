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
    public $currency_id;
    public $banks;
    public $currencies;

    protected $rules = [
        'account_name' => 'required',
        'branch_name' => 'required',
        'account_number' => 'required',
        'currency_id' => 'required',
    ];

    protected $messages = [
        'bank_id' => 'Bank is required',
        'account_name.required' => 'Account name is required.',
        'branch_name.required' => 'Branch name is required.',
        'account_number.required' => 'Account number is required.',
        'currency_id.required' => 'currency is required.',
    ];

    public function mount($id){
        $this->zrbBankAccount = ZrbBankAccount::findOrFail(decrypt($id));
        $this->bank_id = $this->zrbBankAccount->bank_id;
        $this->account_name = $this->zrbBankAccount->account_name;
        $this->branch_name = $this->zrbBankAccount->branch_name;
        $this->account_number = $this->zrbBankAccount->account_number;
        $this->currency_id = $this->zrbBankAccount->currency_id;
        $this->currency_id = $this->zrbBankAccount->currency_id;

        $this->currencies = Currency::select('id', 'iso')->get();
        $this->banks = Bank::select('id', 'name')->get();
    }

    public function submit()
    {
        
        if (!Gate::allows('zrb-bank-account-edit')) {
            abort(403);
        }
        $this->validate();
        DB::beginTransaction();
        try {
            $this->zrbBankAccount->update([
                'bank_id' => $this->bank_id,
                'account_name' => $this->account_name,
                'branch_name' => $this->branch_name,
                'account_number' => $this->account_number,
                'currency_id' => $this->currency_id,
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
