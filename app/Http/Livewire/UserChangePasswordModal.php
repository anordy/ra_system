<?php

namespace App\Http\Livewire;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Validation\Rules\Password;
use ZxcvbnPhp\Zxcvbn;

class UserChangePasswordModal extends Component
{

    use LivewireAlert;

    public $password;
    public $password_confirmation;
    public $passwordStrength = 0;


    protected function rules()
    {
        return [
            'password' => ['required', 'confirmed', 'min:10', Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
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

            $this->alert('error', 'Something went wrong, Could you please contact our administrator for assistance?');
        }
    }

    public function mount($id)
    {
        $user = User::find($id);
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.user-change-password-modal');
    }
}
