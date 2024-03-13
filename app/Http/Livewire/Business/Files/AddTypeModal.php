<?php

namespace App\Http\Livewire\Business\Files;

use App\Models\BusinessCategory;
use App\Models\BusinessFileType;
use App\Models\DualControl;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class AddTypeModal extends Component
{

    use CustomAlert, DualControlActivityTrait;

    public $name, $short_name, $description, $is_required, $business_category;

    public $categories;

    protected $rules = [
        'name' => 'required|strip_tag',
        'short_name' => 'required|strip_tag',
        'description' => 'nullable|strip_tag',
        'is_required' => 'required|boolean',
        'business_category' => 'required|strip_tag',
    ];

    protected function mount(){
        $this->categories = BusinessCategory::all();
    }

    public function submit()
    {
        $this->validate();
        try {
            DB::beginTransaction();
            $file_type = BusinessFileType::create([
                'name' => $this->name,
                'short_name' => $this->short_name,
                'description' => $this->description,
                'is_required' => $this->is_required,
                'business_type' => $this->business_category,
                'file_type' => 'pdf'
            ]);
            $this->triggerDualControl(get_class($file_type), $file_type->id, DualControl::ADD, 'adding business file type');
            DB::commit();
            $this->customAlert('success', 'Record submitted successfully', ['timer' => 8000]);
            return redirect()->route('settings.business-files.index');
        } catch(Exception $e){
            DB::rollBack();
            Log::error('BUSINESS-FILES-ADD-TYPE-MODAL', [$e->getMessage()]);
            $this->customAlert('error', DualControl::ERROR_MESSAGE, ['timer' => 2000]);
            return redirect()->route('settings.business-files.index');
        }
    }

    public function render()
    {
        return view('livewire.business.files.add-type-modal');
    }
}
