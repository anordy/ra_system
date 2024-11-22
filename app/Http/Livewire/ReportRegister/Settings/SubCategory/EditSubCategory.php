<?php

namespace App\Http\Livewire\ReportRegister\Settings\SubCategory;

use App\Enum\CustomMessage;
use App\Models\ReportRegister\RgSubCategory;
use App\Models\ReportRegister\RgSubCategoryNotifiable;
use App\Models\Role;
use App\Traits\CustomAlert;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class EditSubCategory extends Component
{
    use CustomAlert;

    public $name, $description, $rgSubCategory, $notifiables = [], $roles = [];

    public function mount($subCategoryId) {
        $this->rgSubCategory = RgSubCategory::with(['notifiables'])->findOrFail(decrypt($subCategoryId), ['id', 'name', 'description']);
        $this->name = $this->rgSubCategory->name;
        $this->description = $this->rgSubCategory->description;
        if ($this->rgSubCategory->notifiables) {
            $this->notifiables = $this->rgSubCategory->notifiables->pluck('role_id')->toArray();
        }
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
            $this->rgSubCategory->name = $this->name;
            $this->rgSubCategory->description = $this->description;

            if (!$this->rgSubCategory->save()) throw new Exception('Failed to update sub category');

            if ($this->rgSubCategory->notifiables) {
                $this->rgSubCategory->notifiables()->delete();
            }

            foreach ($this->notifiables ?? [] as $notifiable) {
                $subNotifiable = RgSubCategoryNotifiable::create([
                    'rg_sub_category_id' => $this->rgSubCategory->id,
                    'role_id' => $notifiable
                ]);

                if (!$subNotifiable) throw new Exception('Failed to save sub category notifiable');
            }

            DB::commit();
            $this->flash('success', 'Sub Category successfully updated', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('REPORT-REGISTER-SETTINGS-EDIT-SUB-CATEGORY-SUBMIT', [$e]);
            $this->customAlert('error', CustomMessage::error());
        }
    }


    public function render()
    {
        return view('livewire.report-register.settings.sub-category.edit');
    }

}
