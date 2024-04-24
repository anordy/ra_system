<?php

namespace App\Http\Livewire\Returns;

use App\Models\DualControl;
use App\Models\TaxType;
use App\Traits\DualControlActivityTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class EditReturnTaxType extends Component
{
    use CustomAlert, DualControlActivityTrait;
    public $taxtype_id;
    public $tax_type;
    public $name;
    public $code;
    public $category;
    public $gfs_code;
    public $old_values;


    protected $rules = [
        'name' => 'required|strip_tag',
        'gfs_code' => 'required|strip_tag',
    ];

    public function mount($taxtype_id)
    {
        $this->taxtype_id = decrypt($taxtype_id);
        $this->tax_type = TaxType::query()->findOrFail($this->taxtype_id, ['id', 'name', 'code', 'gfs_code', 'is_approved']);
        $this->name = $this->tax_type->name;
        $this->code = $this->tax_type->code;
        $this->category = $this->tax_type->category;
        $this->gfs_code = $this->tax_type->gfs_code;
        $this->old_values = [
            'name' => $this->name,
            'gfs_code' => $this->gfs_code,
        ];
    }

    public function update()
    {
        if (!Gate::allows('setting-return-tax-type-edit')) {
            abort(403);
        }
        $this->validate();
        try {
            DB::beginTransaction();
            $payload = [
                'name' => $this->name,
                'gfs_code' => $this->gfs_code,
            ];
            $this->triggerDualControl(get_class($this->tax_type), $this->tax_type->id, DualControl::EDIT, 'editing tax type', json_encode($this->old_values), json_encode($payload));
            DB::commit();
            $this->customAlert('success', DualControl::SUCCESS_MESSAGE);
            redirect()->route('settings.return-config.index');
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception);
            $this->flash('warning', DualControl::ERROR_MESSAGE, [], redirect()->back()->getTargetUrl());
        }
    }

    public function render()
    {
        return view('livewire.returns.edit-return-tax-type');
    }
}
