<?php

namespace App\Http\Livewire\Relief;

use App\Models\Relief\ReliefSponsor;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class ReliefSponsorAddModal extends Component
{
    use CustomAlert;

    public $name;
    public $acronym;
    public $description;

    protected function rules()
    {
        return [
            'name' => 'required|unique:relief_sponsors,name|strip_tag',
            'acronym' => 'required|unique:relief_sponsors,name|strip_tag',
        ];
    }


    public function submit()
    {
        if (!Gate::allows('relief-sponsors-create')) {
            abort(403);
        }
        $this->validate();
        try {
            ReliefSponsor::create([
                'name' => $this->name,
                'acronym' => $this->acronym,
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
        return view('livewire.relief.relief-sponsor-add-modal');
    }
}
