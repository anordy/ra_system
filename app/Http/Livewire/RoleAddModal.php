<?php

namespace App\Http\Livewire;

use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use ZxcvbnPhp\Zxcvbn;

class RoleAddModal extends Component
{

    use LivewireAlert;

    public $name;


    protected function rules()
    {
        return [
            'name' => 'required|min:2|unique:users',
        ];
    }


    public function submit()
    {
        $this->validate();
        try{
            Role::create([
                'name' => $this->name,
            ]);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        }catch(Exception $e){
            Log::error($e);

            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.role-add-modal');
    }
}
