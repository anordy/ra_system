<?php

namespace App\Http\Livewire;


use App\Models\DualControl;
use App\Models\Role;
use App\Models\User;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class UserRoleEditModal extends Component
{

    use LivewireAlert, DualControlActivityTrait;

    public $roles = [];
    // public $fname;
    // public $lname;
    // public $phone;
    // public $gender = '';
    // public $email;
    public $role = '';
    public $user;
    public $old_values;

    public function mount($id)
    {
        $this->roles = Role::all();
        $user = User::find(decrypt($id));
        if (!empty($user)) {
            $this->user = $user;
            $this->role = $user->role_id;
            $this->old_values = [
                'role_id' => $this->role,
                'fname' => $this->user->fname,
                'lname' => $this->user->lname,
                'email' => $this->user->email,
                'phone' => $this->user->phone,
            ];
        }
        else
        {
            Log::error('No result is found, Invalid id');
            abort(404);
        }
    }

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

        if ($this->user->is_approved == DualControl::NOT_APPROVED) {
            $this->alert('error', 'The updated module has not been approved already');
            return;
        }
        DB::beginTransaction();
        try {
            $payload = [
                'role_id' => $this->role,
                'fname' => $this->user->fname,
                'lname' => $this->user->lname,
                'email' => $this->user->email,
                'phone' => $this->user->phone,
            ];

            if ($this->role == $this->old_values['role_id']) {
                $this->alert('error', 'You have selected the same role. Please try again with different one.');
                return;
            }

            $this->triggerDualControl(get_class($this->user), $this->user->id, DualControl::EDIT, 'editing user role ' . $this->user->fname . ' ' . $this->user->lname . '', json_encode($this->old_values), json_encode($payload));
            DB::commit();
            $this->alert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 8000]);
            return redirect()->route('settings.users.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', DualControl::ERROR_MESSAGE);
            return redirect()->route('settings.users.index');
        }
    }

    public function render()
    {
        return view('livewire.user-role-edit-modal');
    }
}
