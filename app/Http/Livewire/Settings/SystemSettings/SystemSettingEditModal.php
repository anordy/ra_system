<?php

namespace App\Http\Livewire\Settings\SystemSettings;

use App\Models\DualControl;
use App\Models\SystemSetting;
use App\Models\SystemSettingCategory;
use App\Traits\DualControlActivityTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class SystemSettingEditModal extends Component
{

    use CustomAlert, DualControlActivityTrait, WithFileUploads;

    public $systemSetting;
    public $name;
    public $unit;
    public $value;
    public $system_setting_category;
    public $old_values;
    public $valueType;
    public $certificateSettings = false;
    public $settingCategory;

    protected function rules()
    {
        return [
            'name' => 'required|strip_tag',
            'unit' => 'required|strip_tag',
            'value' => 'required|strip_tag',
            'value' => 'required_if:valueType,file|max:280',
            'system_setting_category' => 'required',
        ];
    }

    protected $messages = [
        'system_setting_category' => 'System setting category is required',
        'name.required' => 'Name is required.',
        'code.required' => 'Code is required.',
        'value.required' => 'Value is required.',
        'value.required_if:valueType,file' => 'Image size is too large.',
        'unit.required' => 'Unit is required.',
        'description.required' => 'Description is required.',
    ];

    public function mount($id)
    {
        $this->categories = SystemSettingCategory::select('id', 'name', 'code')->get();
        $this->systemSetting = SystemSetting::findOrFail(decrypt($id));
        $this->name = $this->systemSetting->name;
        $this->code = $this->systemSetting->code;
        $this->unit = $this->systemSetting->unit;

        if($this->unit == SystemSetting::INPUT_TIME){
            $this->value = Carbon::createFromFormat('H:i', $this->systemSetting->value)->format('H:i');
        } else {
            $this->value = $this->systemSetting->value;
        }

        $this->description = $this->systemSetting->description;
        $this->system_setting_category = $this->systemSetting->system_setting_category_id;

        $this->old_values = [
            'system_setting_category_id' => $this->system_setting_category,
            'name' => $this->name,
            'code' => $this->code,
            'unit' => $this->unit,
            'value' => $this->value,
            'description' => $this->description,
        ];

        if($this->unit != SystemSetting::INPUT_FILE && $this->unit != SystemSetting::INPUT_TIME){
            $this->valueType = SystemSetting::INPUT_TEXT;
        } else {
            $this->valueType = $this->unit;
        }

        $property = $this->system_setting_category;
        $object = $this->categories->first(function ($item) use ($property) {
            return $item->id == $property;
        });
        $object = SystemSettingCategory::find($this->systemSetting->system_setting_category_id);
        $this->settingCategory = $object->code;
    }


    public function updated($property){
        if ($property == 'system_setting_category'){
            $value = $this->system_setting_category;
            $object = $this->categories->first(function ($item) use ($value) {
                return $item->id == $value;
            });
            $this->settingCategory = $object->code;
        }
    }

    public function submit()
    {
        if (!Gate::allows('system-setting-edit')) {
            abort(403);
       }
        $this->validate();
        DB::beginTransaction();
        try {
            $value = $this->value;
            if ($this->valueType == SystemSetting::INPUT_FILE){
                $valuePath = $this->value->store('/sign', 'local');
                $value = $valuePath;
            }
            $payload = [
                'system_setting_category_id' => $this->system_setting_category,
                'name' => $this->name,
                'unit' => $this->unit,
                'code' => $this->code,
                'value' => $value,
                'description' => $this->description,
            ];
            
            $this->triggerDualControl(get_class($this->systemSetting), $this->systemSetting->id, DualControl::EDIT, 'edit system setting entry', json_encode($this->old_values), json_encode($payload));
            DB::commit();
            $this->customAlert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 10000]);
            $this->flash(
                'success',
                DualControl::SUCCESS_MESSAGE,
                [],
                redirect()
                    ->back()
                    ->getTargetUrl(),
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('warning', DualControl::ERROR_MESSAGE, ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }


    public function render()
    {
        return view('livewire.settings.system-settings.system-setting-edit-modal');
    }
}
