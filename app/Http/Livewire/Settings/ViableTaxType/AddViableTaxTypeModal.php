<?php

namespace App\Http\Livewire\Settings\ViableTaxType;

use App\Enum\GeneralConstant;
use App\Models\DualControl;
use App\Models\TaxType;
use App\Models\ViableTaxTypeChange;
use App\Traits\CustomAlert;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AddViableTaxTypeModal extends Component
{

    use CustomAlert, DualControlActivityTrait;

    public $taxType, $viableTaxes = [], $currentViableTaxes = [], $oldValues, $currentSelectedTaxes = [];


    protected function rules()
    {
        return [
            'currentViableTaxes' => 'required|array',
            'currentViableTaxes.*' => 'required|integer|exists:tax_types,id'
        ];
    }

    public function mount($id)
    {
        $this->taxType = TaxType::findOrFail(decrypt($id), ['id', 'name']);
        $this->viableTaxes = TaxType::query()
            ->main()
            ->select('id', 'name')
            ->where('id', '!=', $this->taxType->id)
            ->orderBy('name', 'ASC')
            ->get();
        $currentViableTaxes = ViableTaxTypeChange::query()
            ->select('viable_tax_types')
            ->where('tax_type_id', $this->taxType->id)
            ->where('is_updated', GeneralConstant::ONE_INT)
            ->first();

        if ($currentViableTaxes && isset($currentViableTaxes->viable_tax_types)) {
            $this->oldValues = $currentViableTaxes->viable_tax_types;
            $payload = json_decode($currentViableTaxes->viable_tax_types, TRUE);
            $selectedIds = [];
            foreach ($payload ?? [] as $item) {
                $selectedIds[] = $item['id'];
            }
            $this->currentViableTaxes = $selectedIds;
            $this->currentSelectedTaxes = $selectedIds;
        }

    }


    public function submit()
    {
        $this->validate();

        if ($this->currentSelectedTaxes == $this->currentViableTaxes) {
            $this->customAlert('warning', 'No different selection has been done, Please choose different viable taxes');
            return;
        }

        try {
            DB::beginTransaction();

            $currentValues = [];

            foreach ($this->currentViableTaxes as $currentViableTax) {
                $currentValues[] = [
                    'id' => $currentViableTax,
                    'name' => TaxType::findOrFail($currentViableTax, ['name'])->name
                ];
            }

            $encodedValues = json_encode($currentValues);

            $viableTaxType = ViableTaxTypeChange::query()
                ->updateOrCreate(
                    [
                        'tax_type_id' => $this->taxType->id
                    ],
                    [
                        'tax_type_id' => $this->taxType->id,
                        'viable_tax_types' => $encodedValues,
                        'is_approved' => GeneralConstant::ZERO_INT
                    ]
                );

            if (!$viableTaxType) throw new Exception('Failed to save viable tax type');

            $newValues = [
                'viable_tax_types' => $encodedValues,
            ];

            $this->triggerDualControl(get_class($viableTaxType), $viableTaxType->id, DualControl::EDIT, 'Updating viable tax types for ' . $this->taxType->name . '', $this->oldValues, json_encode($newValues));

            DB::commit();

            $this->customAlert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 8000]);

            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', DualControl::ERROR_MESSAGE);
        }
    }

    public function render()
    {
        return view('livewire.settings.viable.add-viable-tax-type-modal');
    }
}
