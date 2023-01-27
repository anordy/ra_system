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
    public $full_name;

    protected function rules()
    {
        return [
            'name' => 'required|strip_tag|unique:banks,name',
            'full_name' => 'required|strip_tag|unique:banks,full_name',
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
                'full_name' => $this->full_name,
            ]);

            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        }catch(Exception $e){
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.bank-add-modal');
    }
}
