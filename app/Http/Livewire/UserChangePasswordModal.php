<?php

namespace App\Http\Livewire;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Traits\CustomAlert;
use Illuminate\Validation\Rules\Password;
use ZxcvbnPhp\Zxcvbn;

class UserChangePasswordModal extends Component
{

    use CustomAlert;

    public $password;
    public $password_confirmation;
    public $passwordStrength = 0;
    public $user;


    protected function rules()
    {
        return [
            'password' => ['required', 'confirmed', 'min:8', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
        ];
    }

    public function submit()
    {
        if (!Gate::allows('setting-user-change-password')) {
            abort(403);
        }

        $this->validate();
        try {
            $this->user->update([
                'password' => Hash::make($this->password),
                'is_first_login' => true,
            ]);

            $this->flash('success', 'Password updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);

            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function mount($id)
    {
        $this->user = User::find(decrypt($id));
        if (is_null($this->user)){
            abort(404, 'User not found.');
        }
    }

    public function render()
    {
        return view('livewire.user-change-password-modal');
    }
}
