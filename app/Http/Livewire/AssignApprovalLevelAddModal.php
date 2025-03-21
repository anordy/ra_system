<?php

namespace App\Http\Livewire;

use App\Models\ApprovalLevel;
use App\Models\User;
use App\Models\UserApprovalLevel;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AssignApprovalLevelAddModal extends Component
{
    use CustomAlert;

    public $levels;
    public $level;
    public $user_level;
    public $role_id;
    public $user;

    public function mount($user_id)
    {
        $this->levels = ApprovalLevel::select('id', 'name')->orderByDesc('id')->get();
        $this->user = User::find(decrypt($user_id));
        if(is_null($this->user)){
            abort(404);
        }
    }

    public function rules()
    {
        return [
            'level' => 'required'
        ];
    }

    public function submit()
    {
        $this->validate();
        if ($this->user->is_approved != 1) {
            $this->customAlert('error', 'The selected user is not approved');
            return;
        }

        if (Auth::id() == $this->user->id){
            $this->customAlert('error', 'You can not change your own approval level.');
            return;
        }

        DB::beginTransaction();
        try {
            UserApprovalLevel::where('user_id', $this->user->id)->delete();
            $level = UserApprovalLevel::create([
                'role_id' => $this->user->role_id,
                'user_id' => $this->user->id,
                'approval_level_id' => $this->level,
                'created_by' => Auth::id(),
            ]);
            $userLevel = $this->user->update(['level_id'=>$this->level]);
            if (!$level || !$userLevel) throw new \Exception('Failed to create approval level.');
            DB::commit();
            $this->customAlert('success', 'Approval level saved successfully');
            return redirect()->route('settings.users.index');
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception);
            $this->customAlert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.assign-approval-level-add-modal');
    }
}
