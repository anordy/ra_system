<?php

namespace App\Http\Livewire;

use App\Models\DualControl;
use App\Models\TaxType;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class TaxTypeEditModal extends Component
{

    use LivewireAlert, DualControlActivityTrait;
    public $name;
    public $gfs_code;
    public $taxType;
    public $old_values;

    protected function rules()
    {
        return [
            'name' => 'required|unique:tax_types,name,'.$this->taxType->id.',id',
            'gfs_code' => 'required'
        ];
    }

    public function mount($id)
    {
        $this->taxType = TaxType::find(decrypt($id));
        if (!empty($this->taxType))
        {
            $this->name =  $this->taxType->name;
            $this->gfs_code =  $this->taxType->gfs_code;

            $this->old_values = [
                'name' => $this->name,
                'gfs_code' => $this->gfs_code
            ];
        }
        Log::error('No result is found, Invalid id');
        abort(404);

    }

    public function submit()
    {
        if (!Gate::allows('setting-tax-type-edit')) {
            abort(403);
        }

        $this->validate();
        DB::beginTransaction();
        try {
            
            $payload = [
                'name' => $this->name,
                'gfs_code' => $this->gfs_code
            ];
            
            $this->triggerDualControl(get_class($this->taxType), $this->taxType->id, DualControl::EDIT, 'Editing tax type '.$this->taxType->name, json_encode($this->old_values), json_encode($payload));
            DB::commit();
            $this->alert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 8000]);
            $this->flash('success', DualControl::SUCCESS_MESSAGE, [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', DualControl::ERROR_MESSAGE, ['timer' => 2000]);
        }
    }

    public function render()
    {
        return view('livewire.tax-type-edit-modal');
    }
}
