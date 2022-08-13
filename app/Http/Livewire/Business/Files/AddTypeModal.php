<?php

namespace App\Http\Livewire\Business\Files;

use App\Models\BusinessCategory;
use App\Models\BusinessFileType;
use App\Models\Country;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class AddTypeModal extends Component
{

    use LivewireAlert;

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
        try {
            DB::beginTransaction();
            BusinessFileType::create([
                'name' => $this->name,
                'short_name' => $this->short_name,
                'description' => $this->description,
                'is_required' => $this->is_required,
                'business_type' => $this->business_category,
                'file_type' => 'pdf'
            ]);
            $this->flash('success', 'Business File Type Stored.', [], redirect()->back()->getTargetUrl());
            DB::commit();
        } catch(Exception $e){
            Log::error($e);
            DB::rollBack();
            $this->alert('error', "Couldn't add business file type. Please try again." . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.business.files.add-type-modal');
    }
}
