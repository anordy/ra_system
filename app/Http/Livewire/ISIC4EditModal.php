<?php

namespace App\Http\Livewire;

use App\Models\ISIC3;
use App\Models\ISIC4;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ISIC4EditModal extends Component
{

    use LivewireAlert;
    public $code;
    public $description;
    public $isic3_id;
    public $isic4;
    public $isic3s = [];


    protected function rules()
    {
        return [
            'code' => 'required|unique:isic4s,code,'.$this->isic4->id.',id',
            'description' => 'required',
            'isic3_id' => 'required',
        ];
    }

    public function mount($id)
    {
        $this->isic3s = ISIC3::all();
        $data = ISIC4::find($id);
        $this->isic4 = $data;
        $this->code = $data->code;
        $this->description = $data->description;
        $this->isic3_id = $data->isic3_id;
    }


    public function submit()
    {
        if (!Gate::allows('setting-isic-level-four-edit')) {
            abort(403);
        }

        $this->validate();
        try {
            $this->isic4->update([
                'code' => $this->code,
                'description' => $this->description,
                'isic3_id' => $this->isic3_id,
            ]);
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.isic4-edit-modal');
    }
}
