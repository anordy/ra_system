<?php

namespace App\Http\Livewire\Business\Files;

use App\Models\BusinessFileType;
use App\Models\Country;

use App\Models\DualControl;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class EditTypeModal extends Component
{

    use LivewireAlert, DualControlActivityTrait;

    public $name, $short_name, $description, $is_required, $business_category, $type;

    public $categories;
    public $old_values;

    protected $rules = [
        'name' => 'required|strip_tag',
        'short_name' => 'required|strip_tag',
        'description' => 'nullable|strip_tag',
        'is_required' => 'required',
        'business_category' => 'required',
    ];

    public function mount($id)
    {
        $this->type= BusinessFileType::find($id);
        $this->name = $this->type->name;
        $this->short_name = $this->type->short_name;
        $this->description = $this->type->description;
        $this->is_required = $this->type->is_required;
        $this->business_category = $this->type->business_type;
        $this->old_values = [
            'name' => $this->name,
            'short_name' => $this->short_name,
            'description' => $this->description,
            'is_required' => $this->is_required,
            'business_type' => $this->business_category,
            'file_type' => 'pdf'
        ];

    }

    public function submit()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $payload = [
                'name' => $this->name,
                'short_name' => $this->short_name,
                'description' => $this->description,
                'is_required' => $this->is_required,
                'business_type' => $this->business_category,
                'file_type' => 'pdf'
            ];
            $this->triggerDualControl(get_class($this->type), $this->type->id, DualControl::EDIT, 'editing business file type', json_encode($this->old_values), json_encode($payload));
            DB::commit();
            $this->alert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 8000]);
            return redirect()->route('settings.business-files.index');

        } catch (Exception $e) {
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
