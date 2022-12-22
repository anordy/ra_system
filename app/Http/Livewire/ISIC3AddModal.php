<?php

namespace App\Http\Livewire;

use App\Models\ISIC2;
use App\Models\ISIC3;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ISIC3AddModal extends Component
{

    use LivewireAlert;

    public $code;
    public $description;
    public $isic2_id;
    public $isic2s = [];


    protected function rules()
    {
        return [
            'code' => 'required|unique:isic3s,code',
            'description' => 'required',
            'isic2_id' => 'required',
        ];
    }

    public function mount(){
        $this->isic2s = ISIC2::all();
    }


    public function submit()
    {
        if (!Gate::allows('setting-isic-level-three-add')) {
            abort(403);
        }

        $this->validate();
        try{
            ISIC3::create([
                'code' => $this->code,
                'description' => $this->description,
                'isic2_id' => $this->isic2_id,
            ]);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        }catch(Exception $e){
            Log::error($e);

            $this->alert('error', 'Something went wrong, Could you please contact our administrator for assistance?');
        }
    }

    public function render()
    {
        return view('livewire.isic3-add-modal');
    }
}
