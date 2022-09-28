<?php

namespace App\Http\Livewire;

use Exception;
use App\Models\Role;
use App\Models\User;
use ZxcvbnPhp\Zxcvbn;
use Livewire\Component;
use App\Events\SendMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Notifications\NewUserNotification;
use Illuminate\Notifications\Notification;
use App\Notifications\DatabaseNotification;
use Jantinnerezo\LivewireAlert\LivewireAlert;

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
            'fname' => 'required|min:2|alpha',
            'lname' => 'required|min:2|alpha',
            'email' => 'required|email|unique:users,email',
            'gender' => 'required|in:M,F',
            'role' => 'required|exists:roles,id',
            'password' => ['required', 'confirmed', 'min:8', Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
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
        if (!Gate::allows('setting-user-add')) {
            abort(403);
        }

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
                    $subject = 'New '.Role::find($this->role)->name. ' created',
                    $message = 'New '.Role::find($this->role)->name.' ' .$user->fullname() . ' created by ' .auth()->user()->fname.' '.auth()->user()->lname,
                    $href = 'settings.users.index',
                    $hrefText = 'View'
                ));
            }

            $payload = [
                'email' => $this->email,
                'full_name' => "{$this->fname} {$this->lname}",
                'password' => $this->password
            ];

            event(new SendMail('admin-registration', $payload));

            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
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
