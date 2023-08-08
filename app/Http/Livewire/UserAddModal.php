<?php

namespace App\Http\Livewire;

use App\Jobs\User\SendRegistrationEmail;
use App\Jobs\User\SendRegistrationSMS;
use App\Models\ApprovalLevel;
use App\Events\SendMail;
use App\Events\SendSms;
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
use App\Traits\CustomAlert;
use Livewire\Component;

class UserAddModal extends Component
{

    use CustomAlert, VerificationTrait, DualControlActivityTrait;

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
    public $levels;
    public $level_id;
    public $isAdmin = false;
    public $accessLevel = false;
    public $adminCheck;
    public $override_otp;

    public function mount()
    {
        $this->roles = Role::where('is_approved',1)->get();
        $this->levels = ApprovalLevel::select('id', 'name')->orderByDesc('id')->get();
        $this->adminCheck = Role::where('name', Role::ADMINISTRATOR)->value('id');
    }

    public function updated($property){
        if ($property == 'role'){
            if ($this->role == $this->adminCheck){
                $this->accessLevel = true;
            } else{
                $this->accessLevel = false;
                $this->level_id = null;
            }
        }
    }

    protected function rules()
    {
        return [
            'fname' => 'required|min:2|alpha',
            'lname' => 'required|min:2|alpha',
            'email' => 'required|email|unique:users,email|ends_with:zanrevenue.org,egaz.go.tz,ubx.co.tz',
            'gender' => 'required|in:M,F',
            'role' => 'required|exists:roles,id',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'override_otp' => 'required|in:0,1'
        ];
    }

    protected $validationAttributes = [
        'fname' => 'first name',
        'lname' => 'last name',
    ];

    public function messages(){
        return [
            'override_otp.required' => 'Please specify if users can OTP and use security questions by default.'
        ];
    }

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
                'level_id' => $this->level_id,
                'password' => Hash::make($this->password),
                'override_otp' => $this->override_otp
            ]);

            // Sign user
            $this->sign($user);

            $this->triggerDualControl(get_class($user), $user->id, DualControl::ADD, 'adding new user '.$this->fname.' '.$this->lname.'');

            $admins = User::whereHas('role', function ($query) {
                $query->where('name', 'Administrator');
            })->get();

            foreach ($admins as $admin) {
                $admin->notify(new DatabaseNotification(
                    $subject = 'NEW USER CREATED',
                    $message = 'New ' . Role::find($this->role)->name ?? 'N/A' . ' ' . $user->fullname() . ' created by ' . auth()->user()->fname . ' ' . auth()->user()->lname,
                    $href = 'settings.users.index',
                ));
            }

            DB::commit();

            event(new SendSms('user_add', $user->id));
            event(new SendMail('user_add', $user->id));

            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.user-add-modal');
    }
}
