<?php

namespace App\Http\Livewire\Settings\SystemSettings;

use App\Models\DualControl;
use App\Models\SystemSettingCategory;
use App\Traits\DualControlActivityTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class SystemSettingCategoryEditModal extends Component
{
    use LivewireAlert, DualControlActivityTrait;

    public $systemSettingCategory;
    public $name;
    public $description;
    public $old_values;

    protected function rules()
    {
        return [
            'name' => 'required',
            'description' => 'required',
        ];
    }

    public function mount($id)
    {
        $this->systemSettingCategory = SystemSettingCategory::findOrFail(decrypt($id));
        $this->name = $this->systemSettingCategory->name;
        $this->description = $this->systemSettingCategory->description;

        $this->old_values = [
            'name' => $this->name,
            'description' => $this->description,
        ];
    }

    public function submit()
    {
        if (!Gate::allows('setting-penalty-rate-edit')) {
            abort(403);
        }
        $this->validate();
        DB::beginTransaction();
        try {
            $payload = [
                'name' => $this->name,
                'description' => $this->description,
            ];
            $this->triggerDualControl(get_class($this->systemSettingCategory), $this->systemSettingCategory->id, DualControl::EDIT, 'edit system setting category', json_encode($this->old_values), json_encode($payload));
            DB::commit();
            $this->alert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 10000]);
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
            $this->alert('warning', DualControl::ERROR_MESSAGE, ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }

    public function render()
    {
        return view('livewire.settings.system-settings.system-setting-category-edit-modal');
    }
}
