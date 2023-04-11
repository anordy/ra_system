<?php

namespace App\Http\Livewire;

use App\Models\Bank;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class BankEditModal extends Component
{

    use CustomAlert;

    public $name;
    public $full_name;
    public $bank;

    protected function rules()
    {
        return [
            'name' => 'required|strip_tag|unique:banks,name,'.$this->bank->id.',id',
            'full_name' => 'required|strip_tag|unique:banks,full_name,'.$this->bank->id.',id',
        ];
    }

    public function mount($id)
    {
        $id = decrypt($id);
        $data = Bank::find($id);
        if(is_null($data)){
            abort(404);
        }
        $this->bank = $data;
        $this->name = $data->name;
        $this->full_name = $data->full_name;
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
                'full_name' => $this->full_name,
            ]);
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.bank-edit-modal');
    }
}
