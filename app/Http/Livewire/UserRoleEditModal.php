<?php

namespace App\Http\Livewire;


use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class UserRoleEditModal extends Component
{
    
    use LivewireAlert;

    public $roles = [];
    // public $fname;
    // public $lname;
    // public $phone;
    // public $gender = '';
    // public $email;
    public $role = '';
    public $user;


    protected function rules()
    {
        return [
            'role' => 'required|exists:roles,id',
        ];
    }

    public function submit()
    {
        if (!Gate::allows('setting-user-add')) {
            abort(403);
        }

        $this->validate();
        try {
            $this->user->update([
                'role_id' => $this->role,
            ]);
            $this->flash('success', 'Role updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact our support desk for help');
        }
    }

    public function mount($id)
    {
        $this->roles = Role::all();
        $user = User::find($id);
        $this->user = $user;
        $this->role = $user->role_id;
    }
    public function render()
    {
        return view('livewire.user-role-edit-modal');
    }
}
