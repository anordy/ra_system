<?php

namespace App\Http\Livewire;

use App\Models\BusinessCategory;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class BusinessCatEditModal extends Component
{

    use CustomAlert;
    public $name;
    public $businessCategory;

    protected function rules()
    {
        return [
            'name' => 'required|strip_tag|unique:business_categories,name,' . $this->businessCategory->id . ',id',
        ];
    }

    public function mount($id)
    {
        $id = decrypt($id);
        $data = BusinessCategory::find($id);
        if (is_null($data)) {
            abort(404);
        }
        $this->businessCategory = $data;
        $this->name = $data->name;
    }

    public function submit()
    {
        if (!Gate::allows('setting-business-category-edit')) {
            abort(403);
        }

        $this->validate();
        try {
            $this->businessCategory->update([
                'name' => $this->name,
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
        return view('livewire.business-cat-edit-modal');
    }
}
