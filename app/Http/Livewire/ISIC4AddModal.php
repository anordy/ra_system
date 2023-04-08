<?php

namespace App\Http\Livewire;

use App\Models\ISIC3;
use App\Models\ISIC4;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class ISIC4AddModal extends Component
{

    use CustomAlert;

    public $code;
    public $description;
    public $isic3_id;
    public $isic3s = [];


    protected function rules()
    {
        return [
            'code' => 'required|strip_tag|unique:isic3s,code',
            'description' => 'required|strip_tag',
            'isic3_id' => 'required',
        ];
    }

    public function mount(){
        $this->isic3s = ISIC3::all();
    }


    public function submit()
    {
        if (!Gate::allows('setting-isic-level-four-add')) {
            abort(403);
        }

        $this->validate();
        try{
            ISIC4::create([
                'code' => $this->code,
                'description' => $this->description,
                'isic3_id' => $this->isic3_id,
            ]);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        }catch(Exception $e){
            Log::error($e);

            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.isic4-add-modal');
    }
}
