<?php

namespace App\Http\Livewire;

use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use ZxcvbnPhp\Zxcvbn;

class RoleEditModal extends Component
{

    use LivewireAlert;
    public $name;
    public $role;


    protected function rules()
    {
        return [
            'name' => 'required|min:2|unique:roles,name,'.$this->role->id.',id',
        ];
    }

    public function submit()
    {
        $this->validate();
        try {
            $this->user->update([
                'name' => $this->name,
            ]);
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function mount($id)
    {
        $data = Role::find($id);
        $this->role = $data;
        $this->name = $data->name;
    }

    public function render()
    {
        return view('livewire.role-edit-modal');
    }
}
