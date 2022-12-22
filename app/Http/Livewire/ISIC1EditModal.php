<?php

namespace App\Http\Livewire;

use App\Models\ISIC1;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ISIC1EditModal extends Component
{

    use LivewireAlert;
    public $code;
    public $description;
    public $isic1;

    protected function rules()
    {
        return [
            'code' => 'required|unique:isic1s,code,'.$this->isic1->id.',id',
            'description' => 'required'
        ];
    }

    public function mount($id)
    {
        $data = ISIC1::find($id);
        $this->isic1 = $data;
        $this->code = $data->code;
        $this->description = $data->description;
    }

    public function submit()
    {
        if (!Gate::allows('setting-isic-level-one-edit')) {
            abort(403);
        }

        $this->validate();
        try {
            $this->isic1->update([
                'code' => $this->code,
                'description' => $this->description,
            ]);
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, Could you please contact our administrator for assistance?');
        }
    }

    public function render()
    {
        return view('livewire.isic1-edit-modal');
    }
}
