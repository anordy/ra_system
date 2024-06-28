<?php

namespace App\Http\Livewire;

use App\Models\ISIC1;
use App\Models\ISIC2;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class ISIC2AddModal extends Component
{

    use CustomAlert;

    public $code;
    public $description;
    public $isic1_id;
    public $isic1s = [];


    protected function rules()
    {
        return [
            'code' => 'required|strip_tag|unique:isic2s,code',
            'description' => 'required|strip_tag',
            'isic1_id' => 'required',
        ];
    }

    public function mount()
    {
        $this->isic1s = ISIC1::all();
    }


    public function submit()
    {
        if (!Gate::allows('setting-isic-level-two-add')) {
            abort(403);
        }

        $this->validate();
        try {
            ISIC2::create([
                'code' => $this->code,
                'description' => $this->description,
                'isic1_id' => $this->isic1_id,
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
        return view('livewire.isic2-add-modal');
    }
}
