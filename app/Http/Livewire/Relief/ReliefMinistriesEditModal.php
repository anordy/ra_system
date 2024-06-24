<?php

namespace App\Http\Livewire\Relief;

use App\Models\Relief\ReliefMinistry;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class ReliefMinistriesEditModal extends Component
{

    use CustomAlert;

    public $name;
    public $type;
    public $description;
    public $reliefProjectSection;

    protected function rules()
    {
        return [
            'name' => 'required|unique:relief_ministries,name,' . $this->reliefProjectSection->id . ',id|strip_tag',
            'type' => 'required|strip_tag',
        ];
    }

    public function mount($id)
    {
        $data = ReliefMinistry::findorFail(decrypt($id));
        $this->reliefProjectSection = $data;
        $this->name = $data->name;
        $this->type = $data->type;
        $this->description = $data->description;
    }

    public function submit()
    {
        if (!Gate::allows('relief-ministries-edit')) {
            abort(403);
        }
        $this->validate();
        try {
            $this->reliefProjectSection->update([
                'name' => $this->name,
                'type' => $this->type,
            ]);
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
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
        return view('livewire.relief.ministries.edit-modal');
    }
}
