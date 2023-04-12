<?php

namespace App\Http\Livewire;

use App\Models\DualControl;
use App\Models\TaxType;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class TaxTypeAddModal extends Component
{

    use CustomAlert, DualControlActivityTrait;

    public $name;


    protected function rules()
    {
        return [
            'name' => 'required|unique:tax_types|strip_tag',
        ];
    }


    public function submit()
    {
        if (!Gate::allows('setting-tax-type-add')) {
            abort(403);
        }

        $this->validate();
        $newName = strtolower($this->name);
        $code = str_replace(' ', '-', $newName);
        DB::beginTransaction();
        try{
            $tax_type = TaxType::create([
                'code' => $code,
                'name' => $this->name,
            ]);
            $this->triggerDualControl(get_class($tax_type), $tax_type->id, DualControl::ADD, 'adding tax type '.$this->name);
            DB::commit();
            $this->flash('success', DualControl::SUCCESS_MESSAGE, [], redirect()->back()->getTargetUrl());
        }catch(Exception $e){
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', DualControl::ERROR_MESSAGE);
        }
    }

    public function render()
    {
        return view('livewire.tax-type-add-modal');
    }
}
