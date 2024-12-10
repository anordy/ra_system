<?php

namespace App\Http\Livewire\ReportRegister\Settings\Category;

use App\Enum\CustomMessage;
use App\Models\ReportRegister\RgCategory;
use App\Traits\CustomAlert;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class EditCategory extends Component
{
    use CustomAlert;

    public $name, $description, $rgCategory;

    public function mount($categoryId) {
        $this->rgCategory = RgCategory::findOrFail(decrypt($categoryId), ['id', 'name', 'description']);
        $this->name = $this->rgCategory->name;
        $this->description = $this->rgCategory->description;
    }

    protected function rules()
    {
        return [
            'name' => 'required|max:100|alpha_gen',
            'description' => 'nullable|max:255|alpha_gen',
        ];
    }

    public function submit()
    {
        $this->validate();

        try {
            $this->rgCategory->name = $this->name;
            $this->rgCategory->description = $this->description;

            if (!$this->rgCategory->save()) throw new Exception('Failed to update category');

            $this->flash('success', 'Category successfully updated', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error('REPORT-REGISTER-SETTINGS-CATEGORY-EDIT', [$e]);
            $this->customAlert('error', CustomMessage::error());
        }
    }


    public function render()
    {
        return view('livewire.report-register.settings.category.edit');
    }

}
