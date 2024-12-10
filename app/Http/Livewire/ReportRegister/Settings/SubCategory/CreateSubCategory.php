<?php

namespace App\Http\Livewire\ReportRegister\Settings\SubCategory;

use App\Enum\CustomMessage;
use App\Enum\ReportRegister\RgRequestorType;
use App\Models\ReportRegister\RgCategory;
use App\Models\ReportRegister\RgSubCategory;
use App\Models\ReportRegister\RgSubCategoryNotifiable;
use App\Models\Role;
use App\Traits\CustomAlert;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CreateSubCategory extends Component
{
    use CustomAlert;

    public $name, $description, $categoryId, $categoryName, $notifiables, $roles = [];

    public function mount($categoryId) {
        $this->categoryId = decrypt($categoryId);
        $this->categoryName = RgCategory::findOrFail($this->categoryId, ['name'])->name;
        $this->roles = Role::query()->select('id', 'name')->orderBy('name', 'Asc')->get();
    }

    protected function rules()
    {
        return [
            'name' => 'required|max:100|alpha_gen',
            'description' => 'nullable|max:255|alpha_gen',
            'notifiables' => 'required|array',
            'notifiables.*' => 'required|integer|exists:roles,id'
        ];
    }

    public function submit()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $rgSubCategory = RgSubCategory::create([
                'name' => $this->name,
                'description' => $this->description,
                'requester_type' => RgRequestorType::TAXPAYER,
                'rg_category_id' => $this->categoryId
            ]);

            foreach ($this->notifiables ?? [] as $notifiable) {
                $subNotifiable = RgSubCategoryNotifiable::create([
                   'rg_sub_category_id' => $rgSubCategory->id,
                   'role_id' => $notifiable
                ]);

                if (!$subNotifiable) throw new Exception('Failed to save sub category notifiable');
            }

            if (!$rgSubCategory) throw new Exception('Failed to save sub category');

            DB::commit();
            $this->flash('success', 'Sub Category successfully created', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('REPORT-REGISTER-SETTINGS-SUB-CATEGORY-CREATE', [$e]);
            $this->customAlert('error', CustomMessage::error());
        }
    }


    public function render()
    {
        return view('livewire.report-register.settings.sub-category.create');
    }

}
