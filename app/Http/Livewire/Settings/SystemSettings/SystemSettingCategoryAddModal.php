<?php

namespace App\Http\Livewire\Settings\SystemSettings;

use App\Models\SystemSettingCategory;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class SystemSettingCategoryAddModal extends Component
{
    use LivewireAlert;
    public $name;
    public $description;

    protected $rules = [
        'name' => 'required',
        'description' => 'required',
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
            SystemSettingCategory::create([
                'name' => $this->name,
                'description' => $this->description,
                'created_at' => Carbon::now()
            ]);
            
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
        return view('livewire.settings.system-settings.system-setting-category-add-modal');
    }
}
