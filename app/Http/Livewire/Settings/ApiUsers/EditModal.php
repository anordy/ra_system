<?php

namespace App\Http\Livewire\Settings\ApiUsers;

use App\Models\ApiUser;
use App\Models\ApprovalLevel;
use App\Models\DualControl;
use App\Models\Role;
use App\Models\User;
use App\Traits\DualControlActivityTrait;
use App\Traits\VerificationTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class EditModal extends Component
{
    use LivewireAlert, DualControlActivityTrait, VerificationTrait;

    public $app_name;
    public $app_url;
    public $username;
    public $user;
    public $old_values;

    public function mount($id)
    {
        $this->user = ApiUser::find($id);
        $this->app_name = $this->user->app_name;
        $this->app_url = $this->user->app_url;
        $this->username = $this->user->username;
        $this->old_values = [
            'app_name' => $this->app_name,
            'app_url' => $this->app_url,
            'username' => $this->username,
        ];
    }

    protected function rules()
    {
        return [
            'app_name' => 'required|max:50|min:2|strip_tag',
            'app_url' => 'required|max:100|min:2',
            'username' => 'required|string',
        ];
    }

    public function submit()
    {
        if (!Gate::allows('setting-user-edit')) {
            abort(403);
        }

        $this->validate();

        if ($this->user->is_approved == DualControl::NOT_APPROVED) {
            $this->alert('error', 'The updated module has not been approved already');
            return;
        }
        DB::beginTransaction();
        try {
            // Verify previous user information
            if (!$this->verify($this->user)) {
                throw new Exception('Could not verify user information.');
            }

            $payload = [
                'app_name' => $this->app_name,
                'app_url' => $this->app_url,
                'username' => $this->username,
            ];

            // Sign User
            $this->sign($this->user);

            $this->triggerDualControl(get_class($this->user), $this->user->id, DualControl::EDIT, 'editing api user ' . $this->user->app_name, json_encode($this->old_values), json_encode($payload));
            DB::commit();
            $this->alert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 8000]);
            return redirect()->route('settings.api-users.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', DualControl::ERROR_MESSAGE, ['timer' => 2000]);
            return redirect()->route('settings.api-users.index');
        }
    }

    public function render()
    {
        return view('livewire.settings.api-users.edit-modal');
    }
}
