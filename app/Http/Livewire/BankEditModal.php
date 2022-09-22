<?php

namespace App\Http\Livewire;

use App\Models\Bank;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BankEditModal extends Component
{

    use LivewireAlert;

    public $name;
    public $bank;

    protected function rules()
    {
        return [
            'name' => 'required|unique:banks,name,'.$this->bank->id.',id',
        ];
    }

    public function mount($id)
    {
        $data = Bank::find($id);
        $this->bank = $data;
        $this->name = $data->name;
    }

    public function submit()
    {
        if (!Gate::allows('setting-bank-edit')) {
            abort(403);
        }

        $this->validate();
        try {
            $this->bank->update([
                'name' => $this->name,
            ]);
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.bank-edit-modal');
    }
}
