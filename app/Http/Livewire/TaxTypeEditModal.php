<?php

namespace App\Http\Livewire;

use App\Models\TaxType;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class TaxTypeEditModal extends Component
{

    use LivewireAlert;
    public $name;
    public $taxType;

    protected function rules()
    {
        return [
            'name' => 'required|unique:tax_types,name,'.$this->taxType->id.',id',
        ];
    }

    public function mount($id)
    {
        $data = TaxType::find($id);
        $this->taxType = $data;
        $this->name = $data->name;
    }

    public function submit()
    {
        if (!Gate::allows('setting-tax-type-edit')) {
            abort(403);
        }

        $this->validate();
        try {
            $this->taxType->update([
                'name' => $this->name,
            ]);
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.tax-type-edit-modal');
    }
}
