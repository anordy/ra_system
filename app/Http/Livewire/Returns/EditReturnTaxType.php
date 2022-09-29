<?php

namespace App\Http\Livewire\Returns;

use App\Models\Currency;
use App\Models\TaxType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class EditReturnTaxType extends Component
{
    use LivewireAlert;
    public $taxtype_id;
    public $tax_type;
    public $name;
    public $code;
    public $category;
    public $gfs_code;


    protected $rules = [
        'name' => 'required',
        'gfs_code' => 'required',
    ];

    public function mount($taxtype_id)
    {
        $this->taxtype_id = decrypt($taxtype_id);
        $this->tax_type = TaxType::query()->findOrFail($this->taxtype_id);
        $this->name = $this->tax_type->name;
        $this->code = $this->tax_type->code;
        $this->category = $this->tax_type->category;
        $this->gfs_code = $this->tax_type->gfs_code;
    }

    public function update()
    {
        if (!Gate::allows('setting-return-tax-type-edit')) {
            abort(403);
        }
        $this->validate();
        DB::beginTransaction();
        try {
            $this->tax_type->name = $this->name;
            $this->tax_type->gfs_code = $this->gfs_code;
            $this->tax_type->save();
            DB::commit();
            $this->flash('success', 'Record updated successfully');
            redirect()->route('settings.return-config.index');
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception);
            $this->flash('warning', 'Something went wrong', [], redirect()->back()->getTargetUrl());
        }
    }

    public function render()
    {
        return view('livewire.returns.edit-return-tax-type');
    }
}
