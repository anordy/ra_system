<?php

namespace App\Http\Livewire;

use App\Imports\ISIC4Import;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Traits\CustomAlert;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Validators\ValidationException;

class ISIC4ImportModal extends Component
{
    use WithFileUploads;
    use CustomAlert;

    public $file;


    protected function rules()
    {
        return [
            'file' => 'required|max:1024', // 1 MB = 1024 KB
        ];
    }

    public function submit()
    {
        if (!Gate::allows('setting-isic-level-four-add')) {
            abort(403);
        }

        $this->validate();
        try {
            $import = new ISIC4Import;
            $import->import($this->file);
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        } catch (ValidationException $e) {
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            if (count($e->failures()) > 0) {
                $errorName = str_replace('.', '', $e->failures()[0]->errors()[0]);
                $errorRowNo  = $e->failures()[0]->row();
                $this->customAlert('error', $errorName . ' on row ' . $errorRowNo . ' on your excel');
            } else {
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            }
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
        return view('livewire.isic4-import-modal');
    }
}
