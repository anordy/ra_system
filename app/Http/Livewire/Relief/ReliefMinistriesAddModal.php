<?php

namespace App\Http\Livewire\Relief;

use App\Models\Relief\ReliefMinistry;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;

class ReliefMinistriesAddModal extends Component
{

    use CustomAlert;

    public $name;
    public $type;
    public $description;

    public function mount()
    {
        $this->type = "Government";
    }

    protected function rules()
    {
        return [
            'name' => 'required|unique:relief_ministries,name|strip_tag',
            'type' => 'required',
        ];
    }


    public function submit()
    {
        if (!Gate::allows('relief-ministries-create')) {
            abort(403);
        }
        $this->validate();
        try {
            ReliefMinistry::create([
                'name' => $this->name,
                'type' => $this->type,
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
        return view('livewire.relief.ministries.add-modal');
    }
}
