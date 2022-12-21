<?php

namespace App\Http\Livewire\Returns\StampDuty;

use App\Models\BusinessFileType;
use App\Models\Country;

use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class EditStampDutyModal extends Component
{

    use LivewireAlert;

    public $name, $short_name, $description, $is_required, $business_category, $type;

    public $categories;

    protected $rules = [
        'name' => 'required',
        'short_name' => 'required',
        'description' => 'nullable',
        'is_required' => 'required',
        'business_category' => 'required',
    ];

    public function mount($id)
    {
        $type = BusinessFileType::find($id);
        $this->type = $type;
        $this->name = $type->name;
        $this->short_name = $type->short_name;
        $this->description = $type->description;
        $this->is_required = $type->is_required;
        $this->business_category = $type->business_type;
    }

    public function submit()
    {
        $this->validate();
        try {
            $this->type->update([
                'name' => $this->name,
                'short_name' => $this->short_name,
                'description' => $this->description,
                'is_required' => $this->is_required,
                'business_type' => $this->business_category,
                'file_type' => 'pdf'
            ]);
            $this->flash('success', 'Business File Type Updated', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, Could you please contact our administrator for assistance?');
        }
    }


    public function render()
    {
        return view('livewire.business.files.add-type-modal');
    }
}
