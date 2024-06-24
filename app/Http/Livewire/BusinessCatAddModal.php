<?php

namespace App\Http\Livewire;

use App\Models\BusinessCategory;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class BusinessCatAddModal extends Component
{

    use CustomAlert;

    public $name;


    protected function rules()
    {
        return [
            'name' => 'required|strip_tag|unique:business_categories',
        ];
    }


    public function submit()
    {
        if (!Gate::allows('setting-business-category-add')) {
            abort(403);
        }

        $this->validate();
        try {
            BusinessCategory::create([
                'name' => $this->name,
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
        return view('livewire.business-cat-add-modal');
    }
}
