<?php

namespace App\Http\Livewire;

use App\Models\EducationLevel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class EducationLevelAddModal extends Component
{
    use LivewireAlert;
    public $name;

    protected function rules()
    {
        return [
            'name' => 'required|unique:education_levels,name',
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
            DB::commit();
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());

        }

        catch (\Throwable $exception)
        {
            DB::rollBack();
            Log::error($exception);
            $this->alert('error', 'Something went wrong, Could you please contact our administrator for assistance?');
        }

    }

    public function render()
    {
        return view('livewire.education-level-add-modal');
    }
}
