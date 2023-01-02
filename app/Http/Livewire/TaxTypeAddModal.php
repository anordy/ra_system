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

class TaxTypeAddModal extends Component
{

    use LivewireAlert, DualControlActivityTrait;

    public $name;


    protected function rules()
    {
        return [
            'name' => 'required|unique:tax_types',
        ];
    }


    public function submit()
    {
        if (!Gate::allows('setting-tax-type-add')) {
            abort(403);
        }

        $this->validate();
        DB::beginTransaction();
        try{
            $tax_type = TaxType::create([
                'name' => $this->name,
            ]);
            $this->triggerDualControl(get_class($tax_type), $tax_type->id, DualControl::ADD, 'adding tax type');
            DB::commit();
            $this->flash('success', DualControl::SUCCESS_MESSAGE, [], redirect()->back()->getTargetUrl());
        }catch(Exception $e){
            DB::rollBack();
            Log::error($e);
            $this->alert('error', DualControl::ERROR_MESSAGE);
        }
    }

    public function render()
    {
        return view('livewire.tax-type-add-modal');
    }
}
