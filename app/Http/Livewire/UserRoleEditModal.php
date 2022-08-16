<?php

namespace App\Http\Livewire;


use App\Models\Role;
use App\Models\User;
use Exception;
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
            // 'fname' => 'required|min:2|alpha',
            // 'lname' => 'required|min:2|alpha',
            // 'email' => 'required|unique:users,email,'.$this->user->id.',id',
            // 'gender' => 'required|in:M,F',
            'role' => 'required|exists:roles,id',
            // 'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        ];
    }

    // protected $validationAttributes = [
    //     'fname' => 'first name',
    //     'lname' => 'last name',
    // ];

    public function submit()
    {
        $this->validate();
        try {
            $this->user->update([
                // 'fname' => $this->fname,
                // 'lname' => $this->lname,
                'role_id' => $this->role,
                // 'gender' => $this->gender,
                // 'email' => $this->email,
                // 'phone' => $this->phone,
            ]);
            $this->flash('success', 'Role updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function mount($id)
    {
        $this->roles = Role::all();
        $user = User::find($id);
        $this->user = $user;
        // $this->fname = $user->fname;
        // $this->lname = $user->lname;
        // $this->phone = $user->phone;
        // $this->email = $user->email;
        // $this->gender = $user->gender ?? '';
        $this->role = $user->role_id;
    }
    public function render()
    {
        return view('livewire.user-role-edit-modal');
    }
}
