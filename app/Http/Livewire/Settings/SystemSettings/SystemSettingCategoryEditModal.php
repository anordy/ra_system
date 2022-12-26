<?php

namespace App\Http\Livewire\Settings\SystemSettings;

use App\Models\DualControl;
use App\Models\SystemSettingCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class SystemSettingCategoryEditModal extends Component
{
    use LivewireAlert;

    public $systemSettingCategory;
    public $name;
    public $description;

    protected function rules()
    {
        return [
            'name' => 'required',
            'unit' => 'required',
            'value' => 'required|numeric',
        ];
    }

    public function mount($id)
    {
        $this->systemSettingCategory = SystemSettingCategory::findOrFail(decrypt($id));
        $this->name = $this->systemSettingCategory->name;
        $this->description = $this->systemSettingCategory->description;
    }


    public function submit()
    {
        if (!Gate::allows('setting-penalty-rate-edit')) {
            abort(403);
       }
        $this->validate();
        DB::beginTtansaction();
        try {
            $this->systemSettingCategory->update([
                'name' => $this->name,
                'unit' => $this->unit,
                'value' => $this->value,
                'description' => $this->description,
            ]);
            $this->triggerDualControl(get_class($this->systemSettingCategory), $this->systemSettingCategory->id, DualControl::EDIT, 'edit system setting category');
            DB::commit();
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong, Could you please contact our administrator for assistance?');
        }
    }

    public function render()
    {
        return view('livewire.settings.system-settings.system-setting-category-edit-modal');
    }
}
