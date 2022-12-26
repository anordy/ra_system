<?php

namespace App\Http\Livewire\Settings\SystemSettings;

use App\Models\DualControl;
use App\Models\SystemSetting;
use App\Models\SystemSettingCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class SystemSettingEditModal extends Component
{

    use LivewireAlert;

    public $systemSetting;
    public $name;
    public $unit;
    public $value;
    public $system_setting_category;

    protected function rules()
    {
        return [
            'name' => 'required',
            'unit' => 'required',
            'value' => 'required|numeric',
            'system_setting_category' => 'required',
        ];
    }

    public function mount($id)
    {
        $this->categories = SystemSettingCategory::select('id', 'name')->get();
        $this->systemSetting = SystemSetting::findOrFail(decrypt($id));
        $this->name = $this->systemSetting->name;
        $this->unit = $this->systemSetting->unit;
        $this->value = $this->systemSetting->value;
        $this->description = $this->systemSetting->description;
        $this->system_setting_category = $this->systemSetting->system_setting_category_id;
    }


    public function submit()
    {
        if (!Gate::allows('setting-penalty-rate-edit')) {
            abort(403);
       }
        $this->validate();
        DB::beginTransaction();
        try {
            $this->systemSetting->update([
                'system_setting_category_id' => $this->system_setting_category,
                'name' => $this->name,
                'unit' => $this->unit,
                'value' => $this->value,
                'description' => $this->description,
            ]);
            $this->triggerDualControl(get_class($this->systemSetting), $this->systemSetting->id, DualControl::EDIT, 'edit system setting entry');
            DB::commit();
            $this->alert('success', 'Record added successfully');
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong, Please contact our support desk for help');
        }
    }


    public function render()
    {
        return view('livewire.settings.system-settings.system-setting-edit-modal');
    }
}
