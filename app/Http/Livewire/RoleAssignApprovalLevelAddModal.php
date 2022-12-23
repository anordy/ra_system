<?php

namespace App\Http\Livewire;

use App\Models\ApprovalLevel;
use App\Models\Role;
use App\Models\RolesApprovalLevel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RoleAssignApprovalLevelAddModal extends Component
{
    use LivewireAlert;

    public $levels;
    public $level;
    public $role_id;
    public $role_level;

    public function mount($role_id)
    {
        $this->levels = ApprovalLevel::select('id', 'name')->orderByDesc('id')->get();
        $this->role_id = $role_id;
        $this->role_level = RolesApprovalLevel::query()->select('id', 'role_id', 'approval_level_id')
            ->where('role_id', $this->role_id)->first();
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

        $payload = [
            'role_id' => $this->role_id,
            'approval_level_id' => $this->level,
            'created_by' => Auth::id(),
        ];

        try {
//            $level = ApprovalLevel::query()->select('name')->where('id', $this->level)->first();
//            $role = Role::query()->select('name')->where('id', $this->role_id)->first();
//
//            if ($role->name != 'Executive Secretary' && $level->name == 'checker') {
//                if (empty($surveyFee)) {
//                    $this->alert('error', 'This role is not allowed to have this level of approval');
//                    return;
//                }
//            }


//            if ($role->name != 'Executive Secretary' && $level->name == 'checker') {
//                if (empty($surveyFee)) {
//                    $this->alert('error', 'This role is not allowed to have this level of approval');
//                    return;
//                }
//            }
//
//            if ($role->name == 'Executive Secretary' && $level->name == 'maker') {
//                if (empty($surveyFee)) {
//                    $this->alert('error', 'This role is not allowed to have this level of approval');
//                    return;
//                }
//            }

            RolesApprovalLevel::query()->updateOrCreate(['role_id' => $this->role_id], $payload);
            DB::commit();
            $this->alert('success', 'Record saved successfully');
            return redirect()->route('settings.roles.index');
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.role-assign-approval-level-add-modal');
    }
}
