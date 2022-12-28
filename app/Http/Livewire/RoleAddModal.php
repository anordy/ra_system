<?php

namespace App\Http\Livewire;

use App\Models\DualControl;
use App\Models\Role;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RoleAddModal extends Component
{

    use LivewireAlert, DualControlActivityTrait;

    public $name;
    public $report_to = null;
    public $roles;

    public function mount()
    {
        $this->roles = Role::where('is_approved',1)->get();
    }


    protected function rules()
    {
        return [
            'name' => 'required|unique:roles',
            'report_to' => 'nullable|unique:roles',
        ];
    }


    public function submit()
    {
        if (!Gate::allows('setting-role-add')) {
            abort(403);
        }

        $this->validate();
        DB::beginTransaction();
        try{
            $role = Role::create([
                'name' => $this->name,
                'report_to' => $this->report_to,
            ]);
            $this->triggerDualControl(get_class($role), $role->id, DualControl::ADD, 'adding role');
            DB::commit();
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        }catch(Exception $e){
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.role-add-modal');
    }
}
