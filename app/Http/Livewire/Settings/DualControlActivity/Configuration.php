<?php

namespace App\Http\Livewire\Settings\DualControlActivity;

use App\Models\ApprovalLevel;
use App\Models\DualControlConfigure;
use App\Models\Role;
use App\Models\SubSysModule;
use Livewire\Component;

class Configuration extends Component
{
    public $sub_sys_modules;
    public $module;
    public $levels;
    public $level;
    public $roles;
    public $role;

    public function mount()
    {
        $this->sub_sys_modules = SubSysModule::select('id', 'name')->orderBy('name')->get();
        $this->levels = ApprovalLevel::select('id', 'name')->orderBy('name')->get();
        $this->roles = Role::select('id', 'name')->orderBy('name')->get();

    }

    public function submit()
    {
//        $this->validate();
        $payload = [
          'module_id' => $this->module,
          'level_id' => $this->level,
          'role_id' => $this->role,
        ];

        DualControlConfigure::query()->create($payload);
    }

    public function render()
    {
        return view('livewire.settings.dual-control-activity.configuration');
    }
}
