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

class RoleEditModal extends Component
{

    use LivewireAlert, DualControlActivityTrait;
    public $name;
    public $report_to = null;
    public $roles;
    public $old_values;


    protected function rules()
    {
        return [
            'name' => 'required|unique:roles,name,' . $this->role->id . ',id',
            'report_to' => 'nullable|unique:roles',
        ];
    }

    public function submit()
    {
        if (!Gate::allows('setting-role-edit')) {
            abort(403);
        }

        $this->validate();
        DB::beginTransaction();
        try {
            $payload = [
                'name' => $this->name,
                'report_to' => $this->report_to == 'null' ? null : $this->report_to
            ];
//            $this->role->update($payload);
            $this->triggerDualControl(get_class($this->role), $this->role->id, DualControl::EDIT, 'editing role', json_encode($this->old_values), json_encode($payload));
            DB::commit();
            $this->alert('success', DualControl::SUCCESS_MESSAGE,  ['timer'=>8000]);
            return;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', DualControl::ERROR_MESSAGE);
        }
    }

    public function mount($id)
    {
        $data = Role::find($id);
        $this->role = $data;
        $this->name = $data->name;
        $this->report_to = $data->report_to;

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
