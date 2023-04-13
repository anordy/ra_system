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

class RoleEditModal extends Component
{

    use CustomAlert, DualControlActivityTrait;
    public $name;
    public $report_to = null;
    public $roles;
    public $old_values;
    public $role;


    protected function rules()
    {
        return [
            'name' => 'required|strip_tag',
            'report_to' => 'nullable|strip_tag',
        ];
    }

    public function submit()
    {
        if (!Gate::allows('setting-role-edit')) {
            abort(403);
        }

        $this->validate();
        if ($this->role->is_approved == DualControl::NOT_APPROVED) {
            $this->customAlert('error', 'The updated module has not been approved already');
            return;
        }
        DB::beginTransaction();
        try {
            $payload = [
                'name' => $this->name,
                'report_to' => $this->report_to == 'null' ? null : $this->report_to
            ];
            $this->triggerDualControl(get_class($this->role), $this->role->id, DualControl::EDIT, 'editing role '.$this->role->name, json_encode($this->old_values), json_encode($payload));
            DB::commit();
            $this->customAlert('success', DualControl::SUCCESS_MESSAGE,  ['timer'=>8000]);
            return redirect()->route('settings.roles.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', DualControl::ERROR_MESSAGE);
            return redirect()->route('settings.roles.index');
        }
    }

    public function mount($id)
    {
        $id = decrypt($id);
        $this->role = Role::find($id);
        if (is_null($this->role)) {
            abort(404);
        }
        $this->name = $this->role->name;
        $this->report_to = $this->role->report_to;

        $this->old_values = [
            'name' => $this->name,
            'report_to' => $this->report_to,
        ];
        $this->roles = Role::all();
    }

    public function render()
    {
        return view('livewire.role-edit-modal');
    }
}
