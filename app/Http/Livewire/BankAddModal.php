<?php

namespace App\Http\Livewire;

use App\Models\Bank;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BankAddModal extends Component
{
    use LivewireAlert;

    public $name;

    protected function rules()
    {
        return [
            'name' => 'required|unique:banks,name',
        ];
    }


    public function submit()
    {
        if (!Gate::allows('setting-bank-add')) {
            abort(403);
        }

        $this->validate();

        try{
            Bank::create([
                'name' => $this->name,
            ]);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        }catch(Exception $e){
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact our support desk for help');
        }
    }

    public function render()
    {
        return view('livewire.bank-add-modal');
    }
}
