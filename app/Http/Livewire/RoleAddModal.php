<?php

namespace App\Http\Livewire;

use App\Models\DualControl;
use App\Models\Role;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class RoleAddModal extends Component
{

    use CustomAlert, DualControlActivityTrait;

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
            'name' => 'required|strip_tag|unique:roles',
            'report_to' => 'nullable|strip_tag|exists:roles,id',
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
            $this->triggerDualControl(get_class($role), $role->id, DualControl::ADD, 'adding new role '.$this->name);
            DB::commit();
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        }catch(Exception $e){
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.role-add-modal');
    }
}
