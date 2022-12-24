<?php

namespace App\Http\Livewire\Settings\SystemSettings;

use App\Models\SystemSetting;
use App\Models\SystemSettingCategory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class SystemSettingEditModal extends Component
{

    use LivewireAlert;

    public $systemSettingCategory;
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
        $this->systemSettingCategory = SystemSetting::findOrFail(decrypt($id));
        $this->name = $this->systemSettingCategory->name;
        $this->unit = $this->systemSettingCategory->unit;
        $this->value = $this->systemSettingCategory->value;
        $this->description = $this->systemSettingCategory->description;
        $this->system_setting_category = $this->systemSettingCategory->system_setting_category_id;
    }


    public function submit()
    {
        if (!Gate::allows('setting-penalty-rate-edit')) {
            abort(403);
       }
        $this->validate();
        try {
            $this->systemSettingCategory->update([
                'system_setting_category_id' => $this->system_setting_category,
                'name' => $this->name,
                'unit' => $this->unit,
                'value' => $this->value,
                'description' => $this->description,
            ]);
            $this->alert('success', 'Record added successfully');
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, Could you please contact our administrator for assistance?');
        }
    }


    public function render()
    {
        return view('livewire.settings.system-settings.system-setting-edit-modal');
    }
}
