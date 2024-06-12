<?php

namespace App\Http\Livewire;

use App\Models\ISIC2;
use App\Models\ISIC3;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class ISIC3AddModal extends Component
{

    use CustomAlert;

    public $code;
    public $description;
    public $isic2_id;
    public $isic2s = [];


    protected function rules()
    {
        return [
            'code' => 'required|strip_tag|unique:isic3s,code',
            'description' => 'required|strip_tag',
            'isic2_id' => 'required',
        ];
    }

    public function mount()
    {
        $this->isic2s = ISIC2::all();
    }


    public function submit()
    {
        if (!Gate::allows('setting-isic-level-three-add')) {
            abort(403);
        }

        $this->validate();
        try {
            ISIC3::create([
                'code' => $this->code,
                'description' => $this->description,
                'isic2_id' => $this->isic2_id,
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
        return view('livewire.isic3-add-modal');
    }
}
