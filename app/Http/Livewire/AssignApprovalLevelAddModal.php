<?php

namespace App\Http\Livewire;

use App\Models\ApprovalLevel;
use App\Models\Role;
use App\Models\UserApprovalLevel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class AssignApprovalLevelAddModal extends Component
{
    use LivewireAlert;

    public $levels;
    public $level;
    public $user_level;
    public $role_id;
    public $user;

    public function mount($user_id)
    {
        $this->levels = ApprovalLevel::select('id', 'name')->orderByDesc('id')->get();
        $this->user = User::find($user_id);
    }

    public function rules()
    {
        return [
            'level' => 'required'
        ];
    }

    public function submit()
    {
//        if (!Gate::allows('setting-role-assign-approval-level'))
//        {
//            abort(403);
//        }
        $this->validate();
        if ($this->user->is_approved != 1) {
            $this->alert('error', 'The selected user is not approved');
            return;
        }
        if ($this->user->role->name != 'Administrator') {
            $this->alert('error', 'This level of approval is only allowed for Administrator role only');
            return;
        }
        $payload = [
            'role_id' => $this->user->role_id,
            'user_id' => $this->user->id,
            'approval_level_id' => $this->level,
            'created_by' => Auth::id(),
        ];
        DB::beginTransaction();
        try {

            UserApprovalLevel::create($payload);
            $this->user->update(['level_id'=>$this->level]);
            DB::commit();
            $this->alert('success', 'Record saved successfully');
            return redirect()->route('settings.users.index');
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.assign-approval-level-add-modal');
    }
}
