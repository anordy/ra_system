<?php

namespace App\Http\Livewire;

use App\Models\ISIC1;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ISIC1AddModal extends Component
{

    use LivewireAlert;

    public $code;
    public $description;


    protected function rules()
    {
        return [
            'code' => 'required|strip_tag|unique:isic1s,code',
            'description' => 'required|strip_tag',
        ];
    }


    public function submit()
    {
        if (!Gate::allows('setting-isic-level-one-add')) {
            abort(403);
        }

        $this->validate();
        try{
            ISIC1::create([
                'code' => $this->code,
                'description' => $this->description,
            ]);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        }catch(Exception $e){
            Log::error($e);

            $this->alert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.isic1-add-modal');
    }
}
