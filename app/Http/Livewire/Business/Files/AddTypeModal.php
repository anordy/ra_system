<?php

namespace App\Http\Livewire\Business\Files;

use App\Models\BusinessCategory;
use App\Models\BusinessFileType;
use App\Models\Country;
use App\Models\DualControl;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class AddTypeModal extends Component
{

    use LivewireAlert, DualControlActivityTrait;

    public $name, $short_name, $description, $is_required, $business_category;

    public $categories;

    protected $rules = [
        'name' => 'required',
        'short_name' => 'required',
        'description' => 'nullable',
        'is_required' => 'required',
        'business_category' => 'required',
    ];

    protected function mount(){
        $this->categories = BusinessCategory::all();
    }

    public function submit()
    {
        $this->validate();
        DB::beginTransaction();
        try {
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
            $this->alert('success', 'Record submitted successfully', ['timer' => 8000]);
            return redirect()->route('settings.business-files.index');
        } catch(Exception $e){
            DB::rollBack();
            Log::error($e);
            $this->alert('error', DualControl::ERROR_MESSAGE, ['timer' => 2000]);
            return redirect()->route('settings.business-files.index');
        }
    }

    public function render()
    {
        return view('livewire.business.files.add-type-modal');
    }
}
