<?php

namespace App\Http\Livewire\UpgradeTaxType;

use App\Models\BusinessTaxTypeChange;
use App\Models\BusinessTaxTypeUpgrade;
use App\Traits\UpgradeTaxTypeTrait;
use App\Traits\CustomAlert;
use App\Traits\WorkflowProcesssingTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CreateModal extends Component
{
    use CustomAlert, UpgradeTaxTypeTrait, WorkflowProcesssingTrait;
    public $return;

    public function mount($return)
    {
        $this->return = $return;
    }

    protected $listeners = [
        'submit', 'cancel'
    ];

    public function confirmPopUpModal()
    {
        $this->customAlert('warning', 'Are you sure you want to initiate this action?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Confirm',
            'onConfirmed' => 'submit',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
        ]);
    }

    public function submit()
    {
        DB::beginTransaction();
        try {
            $payload = [
                'business_id' => $this->return['business_id'],
                'taxpayer_id' => $this->return['business']['taxpayer']['id'],
                'from_tax_type_id' => $this->return['tax_type_id'],
                'status' => 'pending',
                'approved_on' => now(),
                'from_tax_type_currency' => $this->return['currency'],
                'category' => 'qualified',
            ];

            $taxTypeChange = BusinessTaxTypeChange::query()->create($payload);

            $taxTypeUpgrade = BusinessTaxTypeUpgrade::create([
               'business_tax_type_change_id'  => $taxTypeChange->id
            ]);

            $this->registerWorkflow(get_class($taxTypeUpgrade), $taxTypeUpgrade->id);
            $this->doTransition('initial', ['status' => 'agree', 'comment' => 'Initiated']);

            DB::commit();
            $this->flash('success', 'Tax Type Upgrade Initiated', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $exception) {
            Log::error('INITIATE-TAX-TYPE-CHANGE', [$exception->getMessage()]);
            $this->customAlert('warning', 'Something went wrong, Please try again');
            DB::rollBack();
        }

    }

    public function render()
    {
        return view('livewire.upgrade-tax-type.create-modal');
    }
}
