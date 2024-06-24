<?php

namespace App\Http\Livewire\Relief;

use App\Models\Bank;
use App\Models\Relief\ReliefProject;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;

class ReliefProjectAddModal extends Component
{

    use CustomAlert;

    public $name;
    public $description;


    protected function rules()
    {
        return [
            'name' => 'required|strip_tag',
            'description' => 'nullable',
        ];
    }


    public function submit()
    {
        if (!Gate::allows('relief-projects-create')) {
            abort(403);
        }
        $this->validate();
        try {
            ReliefProject::create([
                'name' => $this->name,
                'description' => $this->description,
                'created_by' => auth()->user()->id
            ]);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.relief.project.add-modal');
    }
}
