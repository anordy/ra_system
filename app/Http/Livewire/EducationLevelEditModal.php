<?php

namespace App\Http\Livewire;

use App\Models\Bank;
use App\Models\DualControl;
use App\Models\EducationLevel;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class EducationLevelEditModal extends Component
{
    use LivewireAlert, DualControlActivityTrait;

    public $name;
    public $level;
    public $old_values;

    protected function rules()
    {
        return [
            'name' => 'required|unique:education_levels,name,'.$this->level->id.',id',
        ];
    }

    public function mount($id)
    {
        $id = decrypt($id);
        $this->level = EducationLevel::findOrFail($id);
        $this->name = $this->level->name;
        $this->old_values = [
            'name' => $this->name,
        ];

    }

    public function submit()
    {
        if (!Gate::allows('setting-education-level-edit')) {
            abort(403);
        }

        $this->validate();
        DB::beginTransaction();
        try {
            $payload = [
                'name' => $this->name,
            ];
            $this->triggerDualControl(get_class($this->level), $this->level->id, DualControl::EDIT, 'editing education level', json_encode($this->old_values), json_encode($payload));
            DB::commit();
            $this->alert('success', DualControl::SUCCESS_MESSAGE,  ['timer'=>8000]);
            return redirect()->route('settings.education-level.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('success', DualControl::SUCCESS_MESSAGE,  ['timer'=>8000]);
            return redirect()->route('settings.education-level.index');
        }
    }
    public function render()
    {
        return view('livewire.education-level-edit-modal');
    }
}
