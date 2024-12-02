<?php

namespace App\Http\Livewire\Account;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class ChangePassword extends Component
{
    use CustomAlert;

    public $current_password, $new_password, $confirm_password;

    protected function rules()
    {
        return [
            'new_password' => [
                'strip_tag',
                'required',
                'string',
                'min:8',             // must be at least 8 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            'confirm_password' => 'required|same:new_password',
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    return $fail(__('The current password is incorrect.'));
                }
            }],
        ];
    }

    protected $messages = [
        'new_password.regex' => 'Password must contain at least one digit, one uppercase letter, one lowercase letter and special character.',
    ];


    public function submit()
    {
        $this->validate();
        try {
            $user = User::findOrFail(Auth::id());
            $user->password = Hash::make($this->new_password);
            $user->save();
            Auth::login($user);
            session()->flash('success', 'Your password has been changed successful');
            $this->redirect(route('account'));
        } catch (Exception $e) {
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }


    public function render()
    {
        return view('livewire.account.change-password');
    }
}
