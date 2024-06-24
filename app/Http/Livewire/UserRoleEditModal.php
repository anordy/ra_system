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
use App\Traits\CustomAlert;
use Livewire\Component;

class UserRoleEditModal extends Component
{

    use CustomAlert, DualControlActivityTrait;

    public $roles = [];
    public $role = '';
    public $user;
    public $old_values;

    public function mount($id)
    {
        $this->roles = Role::all();
        $this->user = User::find(decrypt($id));
        if (is_null($this->user)) {
            abort(404, 'User not found');
        }
        $this->role = $this->user->role_id;
        $this->old_values = [
            'role_id' => $this->role,
            'fname' => $this->user->fname,
            'lname' => $this->user->lname,
            'email' => $this->user->email,
            'phone' => $this->user->phone,
        ];
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
            $this->customAlert('error', 'The updated module has not been approved already');
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
                $this->customAlert('error', 'You have selected the same role. Please try again with different one.');
                return;
            }

            $this->triggerDualControl(get_class($this->user), $this->user->id, DualControl::EDIT, 'editing user role ' . $this->user->fname . ' ' . $this->user->lname . '', json_encode($this->old_values), json_encode($payload));
            DB::commit();
            $this->customAlert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 8000]);
            return redirect()->route('settings.users.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', DualControl::ERROR_MESSAGE);
            return redirect()->route('settings.users.index');
        }
    }

    public function render()
    {
        return view('livewire.user-role-edit-modal');
    }
}
