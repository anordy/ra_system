<?php

namespace App\Http\Livewire;

use App\Models\ApprovalLevel;
use App\Models\DualControl;
use App\Models\ReportRegister\Department;
use App\Models\Role;
use App\Models\User;

use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class UserEditModal extends Component
{

    use CustomAlert, DualControlActivityTrait;

    public $roles = [];
    public $fname;
    public $lname;
    public $phone;
    public $gender = '';
    public $email;
    // public $role = '';
    public $user;
    public $old_values;
    public $levels;
    public $level_id;
    public $override_otp;
    public $departments = [], $department_id;

    protected function rules()
    {
        return [
            'fname' => 'required|min:2|alpha',
            'lname' => 'required|min:2|alpha',
            'email' => 'required|ends_with:zanrevenue.org,egaz.go.tz,ubx.co.tz,zbs.go.tz,kamisheniardhi.go.tz|unique:users,email,' . $this->user->id . ',id',
            'gender' => 'required|in:M,F',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'override_otp' => 'required|in:0,1',
            'department_id' => 'required|integer'
        ];
    }

    public function messages()
    {
        return [
            'override_otp.required' => 'Please specify if users can OTP and use security questions by default.',
            'department_id.required' => 'User department is required.'
        ];
    }

    protected $validationAttributes = [
        'fname' => 'first name',
        'lname' => 'last name',
    ];

    public function mount($id)
    {
        $this->roles = Role::all();
        $user = User::find(decrypt($id), ['id', 'fname', 'lname', 'phone', 'gender', 'email', 'role_id', 'level_id', 'override_otp', 'department_id']);
        if (is_null($user)) {
            abort(404);
        }
        $this->user = $user;
        $this->levels = ApprovalLevel::select('id', 'name')->get();
        $this->fname = $user->fname;
        $this->level_id = $user->level_id;
        $this->lname = $user->lname;
        $this->phone = $user->phone;
        $this->email = $user->email;
        $this->gender = $user->gender ?? '';
        $this->override_otp = $user->override_otp ? 1 : 0;
        $this->department_id = $user->department_id;
        $this->old_values = [
            'fname' => $this->fname,
            'lname' => $this->lname,
            'gender' => $this->gender,
            'email' => $this->email,
            'phone' => $this->phone,
            'override_otp' => $this->override_otp ? 1 : 0,
            'department_id' => $this->department_id
        ];
        $this->departments = Department::query()->select('id', 'name')->get();
    }

    public function submit()
    {
        if (!Gate::allows('setting-user-edit')) {
            abort(403);
        }

        $this->validate();

        if ($this->user->is_approved == DualControl::NOT_APPROVED) {
            $this->customAlert('error', 'The updated module has not been approved already');
            return;
        }
        DB::beginTransaction();
        try {
            // Verify previous user information
            if (!$this->verify($this->user)) {
                throw new Exception('Could not verify user information.');
            }

            $payload = [
                'fname' => $this->fname,
                'lname' => $this->lname,
                'gender' => $this->gender,
                'email' => $this->email,
                'phone' => $this->phone,
                'level_id' => $this->level_id,
                'override_otp' => $this->override_otp,
                'department_id' => $this->department_id
            ];

            // Sign User
            $this->sign($this->user);

            $this->triggerDualControl(get_class($this->user), $this->user->id, DualControl::EDIT, 'editing user ' . $this->user->fullname() . '', json_encode($this->old_values), json_encode($payload));
            DB::commit();
            $this->customAlert('success', DualControl::SUCCESS_MESSAGE);
            $this->flash('success', DualControl::SUCCESS_MESSAGE, [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', DualControl::ERROR_MESSAGE);
        }
    }

    public function render()
    {
        return view('livewire.user-edit-modal');
    }
}
