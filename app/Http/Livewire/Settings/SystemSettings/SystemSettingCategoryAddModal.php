<?php

namespace App\Http\Livewire\Settings\SystemSettings;

use App\Models\DualControl;
use App\Models\SystemSettingCategory;
use App\Traits\DualControlActivityTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class SystemSettingCategoryAddModal extends Component
{
    use CustomAlert, DualControlActivityTrait;
    public $name;
    public $description;

    protected $rules = [
        'name' => 'required|strip_tag',
        'description' => 'required|strip_tag',
    ];

    protected $messages = [
        'name.required' => 'Name is required.',
        'description.required' => 'Description is required.',
    ];

    public function submit()
    {

        if (!Gate::allows('setting-system-category-add')) {
            abort(403);
        }
        $this->validate();
        DB::beginTransaction();
        try {
            $systemSettingCategory = SystemSettingCategory::create([
                'name' => $this->name,
                'description' => $this->description,
                'created_at' => Carbon::now()
            ]);
            $this->triggerDualControl(get_class($systemSettingCategory), $systemSettingCategory->id, DualControl::ADD, 'adding system setting category');
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
        return view('livewire.settings.system-settings.system-setting-category-add-modal');
    }
}
