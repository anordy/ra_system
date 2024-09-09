<?php

namespace App\Http\Livewire\ReportRegister\Settings\Category;

use App\Enum\CustomMessage;
use App\Enum\ReportRegister\RgRequestorType;
use App\Models\ReportRegister\RgCategory;
use App\Traits\CustomAlert;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CreateCategory extends Component
{
    use CustomAlert;

    public $name, $description;

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
            $rgCategory = RgCategory::create([
                'name' => $this->name,
                'description' => $this->description,
                'requester_type' => RgRequestorType::TAXPAYER
            ]);

            if (!$rgCategory) throw new Exception('Failed to save category');

            $this->flash('success', 'Category successfully created', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error('REPORT-REGISTER-SETTINGS-CATEGORY-CREATE', [$e]);
            $this->customAlert('error', CustomMessage::error());
        }
    }


    public function render()
    {
        return view('livewire.report-register.settings.category.create');
    }

}
