<?php

namespace App\Http\Livewire;

use App\Imports\ISIC2Import;
use Livewire\Component;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Validators\ValidationException;

class ISIC2ImportModal extends Component
{
    use WithFileUploads;
    use LivewireAlert;

    public $file;

    
    protected function rules()
    {
        return [
            'file' => 'required',
        ];
    }

    public function submit(){
        if (!Gate::allows('setting-isic-level-two-add')) {
            abort(403);
        }

        $this->validate();
        try{
            $import = new ISIC2Import;
            $import->import($this->file);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        }catch(ValidationException $e){
            Log::error($e);
            if(count($e->failures()) > 0){
                $errorName = str_replace('.','',$e->failures()[0]->errors()[0]);
                $errorRowNo  = $e->failures()[0]->row();
                $this->alert('error', $errorName.' on row '.$errorRowNo.' on your excel');
            }else{
                $this->alert('error', 'Something went wrong');
            }
            
        }catch(Exception $e){
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
        
    }

    public function render()
    {
        return view('livewire.isic2-import-modal');
    }
}
