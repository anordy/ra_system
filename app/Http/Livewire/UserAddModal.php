<?php

namespace App\Http\Livewire;

use App\Jobs\User\SendRegistrationEmail;
use App\Jobs\User\SendRegistrationSMS;
use App\Models\DualControl;
use App\Models\Role;
use App\Models\User;
use App\Notifications\DatabaseNotification;
use App\Traits\DualControlActivityTrait;
use App\Traits\VerificationTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class UserAddModal extends Component
{

    use LivewireAlert, VerificationTrait, DualControlActivityTrait;

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

//        $role_level = DB::table('roles_approval_levels as ra')->leftJoin('approval_levels as al', 'al.id', '=', 'ra.approval_level_id')
//            ->select('ra.id')->where('ra.role_id', Auth::user()->role_id)->first();
//
//        if (empty($role_level))
//        {
//            $this->alert('error', 'Your level of approval is not supported to register user');
//            return;
//        }

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

            // Get ci_payload
            if (!$this->sign($user)){
                throw new Exception('Failed to verify user data.');
            }

            DB::commit();
            $this->triggerDualControl(get_class($user), $user->id, DualControl::ADD, 'adding user');

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
            $this->alert('error', 'Something went wrong, please contact our support desk for help');
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
