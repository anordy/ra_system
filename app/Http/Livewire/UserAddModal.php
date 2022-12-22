<?php

namespace App\Http\Livewire;

use Exception;
use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use App\Events\SendMail;
use App\Jobs\User\SendRegistrationEmail;
use App\Jobs\User\SendRegistrationSMS;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\DB;
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
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        ];
    }

    protected $validationAttributes = [
        'fname' => 'first name',
        'lname' => 'last name',
    ];

    public function submit()
    {
        if (!Gate::allows('setting-user-add')) {
            abort(403);
        }

        $this->validate();

        //check if the application environment is local or production
        if (config('app.env') == 'local') {
            $this->password = 'password';
        } else {
            $this->password = Str::random(8);
        }

        try {
            DB::beginTransaction();

            $user = User::create([
                'fname' => $this->fname,
                'lname' => $this->lname,
                'role_id' => $this->role,
                'gender' => $this->gender,
                'email' => $this->email,
                'phone' => $this->phone,
                'status' => 1,
                'password' => Hash::make($this->password),
            ]);


            $admins = User::whereHas('role', function ($query) {
                $query->where('name', 'Administrator');
            })->get();

            foreach ($admins as $admin) {
                $admin->notify(new DatabaseNotification(
                    $subject = 'NEW USER CREATED',
                    $message = 'New ' . Role::find($this->role)->name . ' ' . $user->fullname() . ' created by ' . auth()->user()->fname . ' ' . auth()->user()->lname,
                    $href = 'settings.users.index',
                ));
            }
            
            DB::commit();

            if (config('app.env') != 'local') {
                //send SMS of credentials to the added user 
            if ($user->phone) {
                dispatch(new SendRegistrationSMS($this->email, $this->password, $this->fname, $this->phone));
            }

            //send Email of credentials to the added user 
            if ($user->email) {
                dispatch(new SendRegistrationEmail($this->fname, $this->email, $this->password));
            }
            }
            
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong, Could you please contact our administrator for assistance?');
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
