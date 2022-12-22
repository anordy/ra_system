<?php

namespace App\Http\Livewire;

use App\Models\ISIC1;
use App\Models\ISIC2;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ISIC2EditModal extends Component
{

    use LivewireAlert;
    public $code;
    public $description;
    public $isic1_id;
    public $isic2;
    public $isic1s = [];


    protected function rules()
    {
        return [
            'code' => 'required|unique:isic1s,code,'.$this->isic2->id.',id',
            'description' => 'required',
            'isic1_id' => 'required',
        ];
    }

    public function mount($id)
    {
        $this->isic1s = ISIC1::all();
        $data = ISIC2::find($id);
        $this->isic2 = $data;
        $this->code = $data->code;
        $this->description = $data->description;
        $this->isic1_id = $data->isic1_id;
    }


    public function submit()
    {
        if (!Gate::allows('setting-isic-level-two-edit')) {
            abort(403);
        }

        $this->validate();
        try {
            $this->isic2->update([
                'code' => $this->code,
                'description' => $this->description,
                'isic1_id' => $this->isic1_id,
            ]);
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, Could you please contact our administrator for assistance?');
        }
    }

    public function render()
    {
        return view('livewire.isic2-edit-modal');
    }
}
