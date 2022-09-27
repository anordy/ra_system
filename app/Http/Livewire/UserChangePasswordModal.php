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

    public function updatedPassword($password)
    {
        $zxcvbn = new Zxcvbn();
        $weak = $zxcvbn->passwordStrength($password);
        if ($weak['score'] == 4) {
            $this->passwordStrength = 100;
        } elseif ($weak['score'] == 3) {
            $this->passwordStrength = 70;
        } elseif ($weak['score'] == 2) {
            $this->passwordStrength = 50;
        } elseif ($weak['score'] == 1) {
            $this->passwordStrength = 30;
        } else {
            $this->passwordStrength = 0;
        }
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

            $this->alert('error', 'Something went wrong');
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
