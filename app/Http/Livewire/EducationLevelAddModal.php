<?php

namespace App\Http\Livewire;

use App\Models\DualControl;
use App\Models\EducationLevel;
use App\Traits\DualControlActivityTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App\Traits\CustomAlert;

class EducationLevelAddModal extends Component
{
    use CustomAlert, DualControlActivityTrait;
    public $name;

    protected function rules()
    {
        return [
            'name' => 'required|strip_tag|unique:education_levels,name',
        ];
    }
    public function submit()
    {
        if (!Gate::allows('setting-education-level-add')) {
            abort(403);
        }

        $validate = $this->validate([
            'name'=>'required'
        ]);

        DB::beginTransaction();
        try {
            $education = new EducationLevel();
            $education->name = $this->name;
            $education->save();
            $this->triggerDualControl(get_class($education), $education->id, DualControl::ADD, 'adding education level');
            DB::commit();
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());

        }

        catch (\Throwable $exception)
        {
            DB::rollBack();
            Log::error($exception);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }

    }

    public function render()
    {
        return view('livewire.education-level-add-modal');
    }
}
