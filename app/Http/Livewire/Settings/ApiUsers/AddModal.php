<?php

namespace App\Http\Livewire\Settings\ApiUsers;

use App\Models\ApiUser;
use App\Models\DualControl;
use App\Models\User;
use App\Traits\DualControlActivityTrait;
use App\Traits\VerificationTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class AddModal extends Component
{
    use CustomAlert, DualControlActivityTrait, VerificationTrait;

    public $app_name;
    public $app_url;
    public $username;
    public $password;

    protected function rules()
    {
        return [
            'app_name' => 'required|max:50|min:2|strip_tag',
            'app_url' => 'required|max:100|min:2',
            'username' => 'required|string|unique:api_users',
            'password' => 'required|min:6|max:100',
        ];
    }

    public function submit()
    {
        if (!Gate::allows('setting-user-add')) {
            abort(403);
        }

        $this->validate();
        DB::beginTransaction();

        try {
            $api_user = ApiUser::create([
                'app_name' => $this->app_name,
                'app_url' => $this->app_url,
                'username' => $this->username,
                'password' => Hash::make($this->password),
            ]);
            // Sign the saved user
            if (!$this->sign($api_user)) {
                throw new Exception('Failed to sign user');
            }
            $this->triggerDualControl(get_class($api_user), $api_user->id, DualControl::ADD, 'adding new user for API '.$this->app_name);
            DB::commit();
            $this->customAlert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 8000]);
            return redirect()->route('settings.api-users.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', DualControl::ERROR_MESSAGE, ['timer' => 2000]);
            return redirect()->route('settings.api-users.index');
        }
    }

    public function render()
    {
        return view('livewire.settings.api-users.add-modal');
    }
}
