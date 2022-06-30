<?php

namespace App\Http\Livewire;

use App\Models\Role;
use App\Models\User;
use App\Notifications\DatabaseNotification;
use App\Notifications\NewUserNotification;
use Exception;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use ZxcvbnPhp\Zxcvbn;

class UserAddModal extends Component
{

    use LivewireAlert;

    public $roles = [];
    public $fname;
    public $lname;
    public $phone;
    public $gender = '';
    public $email;
    public $role = '';
    public $password;
    public $password_confirmation;
    public $passwordStrength = 0;


    protected function rules()
    {
        return [
            'fname' => 'required|min:2',
            'lname' => 'required|min:2',
            'email' => 'required|email|unique:users,email',
            'gender' => 'required|in:M,F',
            'role' => 'required|exists:roles,id',
            // 'password' => ['required', 'confirmed', 'min:10', Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        ];
    }

    protected $validationAttributes = [
        'fname' => 'first name',
        'lname' => 'last name',
    ];

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
        $this->validate();
        try {
            $user = User::create([
                'fname' => $this->fname,
                'lname' => $this->lname,
                'role_id' => $this->role,
                'gender' => $this->gender,
                'email' => $this->email,
                'phone' => $this->phone,
                'password' => Hash::make($this->password),
            ]);

            $adminRole = Role::where('name', 'Administrator')->first();
            $admins = User::where('role_id', $adminRole->id)->get();

            foreach ($admins as $admin) {
                $admin->notify(new DatabaseNotification(
                    $message = 'New User creaated',
                    $type = 'info',
                    $messageLong = 'New user ' . $user->fullname() . ' created successfully by ' . Auth::user()->fullname(),
                    $href = route('settings.users.index'),
                    $hrefText = 'View'
                ));
            }

            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            dd($e);
            Log::error($e);

            $this->alert('error', 'Something went wrong');
        }
    }



    public function mount()
    {
        $this->roles = Role::all();
    }

    public function render()
    {
        return view('livewire.user-add-modal');
    }
}
