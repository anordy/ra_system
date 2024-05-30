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
use App\Traits\CustomAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class SystemSettingAddModal extends Component
{
    use CustomAlert, DualControlActivityTrait, WithFileUploads;
    public $name;
    public $system_setting_category;
    public $value;
    public $code;
    public $unit;
    public $description;
    public $categories;
    public $valueType;
    public $certificateSettings = false;
    public $settingCategory;

    protected $rules = [
        'name' => 'required|strip_tag',
        'code' => 'required|strip_tag',
        'value' => 'required|strip_tag',
        'value' => 'required_if:valueType,file|max:280',
        'unit' => 'required|strip_tag',
        'description' => 'required|strip_tag',
    ];

    protected $messages = [
        'system_setting_category' => 'System setting category is required',
        'name.required' => 'Name is required.',
        'code.required' => 'Code is required.',
        'value.required' => 'Value is required.',
        'value.required_if:valueType,file' => 'Image size is too large.',
        'unit.required' => 'Unit is required.',
        'description.required' => 'Description is required.',
    ];

    public function mount()
    {
        $this->categories = SystemSettingCategory::select('id', 'name')->get();
        $this->valueType = 'text';
    }

    public function updated($property)
    {
        if ($property == 'system_setting_category') {
            $object = $this->categories->first(function ($item) use ($property) {
                return $item->id == $this->system_setting_category;
            });
            $this->settingCategory = $object->code;
        }
    }
    public function updatedName()
    {
        $this->code = str_replace(" ", "-", $this->name);
    }
    public function submit()
    {

        if (!Gate::allows('system-setting-add')) {
            abort(403);
        }
        $this->validate();
        DB::beginTransaction();
        try {
            $value = $this->value;
            if ($this->valueType == 'file') {
                $valuePath = $this->value->store('/sign', 'local');
                $value = $valuePath;
            }

            $systemSetting = new SystemSetting();
            $systemSetting['system_setting_category_id'] = $this->system_setting_category;
            $systemSetting['name'] = $this->name;
            $systemSetting['code'] = $this->code;
            $systemSetting['value'] = $value;
            $systemSetting['unit'] = $this->unit;
            $systemSetting['description'] = $this->description;
            $systemSetting['created_at'] = Carbon::now();
            $systemSetting->save();

            $this->triggerDualControl(get_class($systemSetting), $systemSetting['id'], DualControl::ADD, 'adding system setting entry');
            DB::commit();
            $this->customAlert('success', 'Record added successfully');
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', 'Something went wrong, please contact our administrator for assistance?');
        }
    }

    public function render()
    {
        return view('livewire.settings.system-settings.system-setting-add-modal');
    }
}
