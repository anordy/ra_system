<?php

namespace App\Http\Livewire\Settings\SystemSettings;

use App\Models\DualControl;
use App\Models\SystemSetting;
use App\Models\SystemSettingCategory;
use App\Traits\DualControlActivityTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class SystemSettingAddModal extends Component
{
    use LivewireAlert, DualControlActivityTrait;
    public $name;
    public $system_setting_category;
    public $value;
    public $code;
    public $unit;
    public $description;
    public $categories;

    protected $rules = [
        'name' => 'required',
        'code' => 'required',
        'value' => 'required',
        'unit' => 'required',
        'description' => 'required',
    ];

    protected $messages = [
        'system_setting_category' => 'System setting category is required',
        'name.required' => 'Name is required.',
        'code.required' => 'Code is required.',
        'value.required' => 'Value is required.',
        'unit.required' => 'Unit is required.',
        'description.required' => 'Description is required.',
    ];

    public function mount(){
        $this->categories = SystemSettingCategory::select('id', 'name')->get();
    }

    public function submit()
    {
        
        if (!Gate::allows('system-setting-add')) {
            abort(403);
        }
        $this->validate();
        DB::beginTransaction();
        try {
            $systemSetting = SystemSetting::create([
                'system_setting_category_id' => $this->system_setting_category,
                'name' => $this->name,
                'code' => $this->code,
                'value' => $this->value,
                'unit' => $this->unit,
                'description' => $this->description,
                'created_at' => Carbon::now()
            ]);
            $this->triggerDualControl(get_class($systemSetting), $systemSetting->id, DualControl::ADD, 'adding system setting entry');
            DB::commit();
            $this->alert('success', 'Record added successfully');
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact our administrator for assistance?');
        }
    }

    public function render()
    {
        return view('livewire.settings.system-settings.system-setting-add-modal');
    }
}
