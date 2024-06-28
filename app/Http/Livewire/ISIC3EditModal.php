<?php

namespace App\Http\Livewire;

use App\Models\ISIC2;
use App\Models\ISIC3;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class ISIC3EditModal extends Component
{

    use CustomAlert;
    public $code;
    public $description;
    public $isic2_id;
    public $isic3;
    public $isic2s = [];


    protected function rules()
    {
        return [
            'code' => 'required|strip_tag|unique:isic3s,code,' . $this->isic3->id . ',id',
            'description' => 'required|strip_tag',
            'isic2_id' => 'required',
        ];
    }

    public function mount($id)
    {
        $id = decrypt($id);
        $data = ISIC3::find($id);
        if (is_null($data)) {
            abort(404);
        }
        $this->isic3 = $data;
        $this->code = $data->code;
        $this->description = $data->description;
        $this->isic2_id = $data->isic2_id;
        $this->isic2s = ISIC2::all();
    }


    public function submit()
    {
        if (!Gate::allows('setting-isic-level-three-edit')) {
            abort(403);
        }

        $this->validate();
        try {
            $this->isic3->update([
                'code' => $this->code,
                'description' => $this->description,
                'isic2_id' => $this->isic2_id,
            ]);
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
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
        return view('livewire.isic3-edit-modal');
    }
}
