<?php

namespace App\Http\Livewire\UpgradeTaxType;

use App\Models\BusinessTaxType;
use App\Models\BusinessTaxTypeChange;
use App\Models\TaxType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class Create extends Component
{
    use CustomAlert;

    public $return;
    public $tax_type;
    public $new_tax_type_id;
    public $reason;

    protected $listeners = [
        'confirm',
    ];

    public function upgradeTaxType()
    {
        $this->customAlert('success', 'The selected business will be upgraded to a new tax type', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Continue',
            'onConfirmed' => 'confirm',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'confirmButtonColor' => '#3085d6',
            'cancelButtonColor' => '#d33',
            'timer' => null,
            'input' => 'date',
            'inputOptions'=> ['TZS','USD'],
            'inputPlaceholder'=>'Select currency for the new tax type',
            'data' => $this->return
        ]);
    }

    public function confirm($value)
    {

        DB::beginTransaction();
        try {
            if ($value['value'] == "")
            {
                $this->customAlert('warning', 'Please select currency for to upgrade to new tax type!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
                return redirect()->back();

            }
            if ($value['value'] == 0)
            {
                $currency = 'TZS';
            }
            if ($value['value'] == 1)
            {
                $currency = 'USD';
            }

            $this->reason = 'reached maximum turnover for the previous tax type';
            $data = $value['data'];

            if ($this->return->taxtype->code === TaxType::HOTEL or $this->return->taxtype->code === TaxType::STAMP_DUTY) {
                $this->tax_type = TaxType::query()->where('code', TaxType::VAT)->first();
                if (is_null($this->tax_type)){
                    abort(404);
                }
                $this->new_tax_type_id = $this->tax_type->id;
            }

            if ($this->return->taxtype->code === TaxType::LUMPSUM_PAYMENT) {
                $this->tax_type = TaxType::query()->where('code', TaxType::STAMP_DUTY)->first();
                if (is_null($this->tax_type)){
                    abort(404);
                }
                $this->new_tax_type_id = $this->tax_type->id;
            }
            $payload = [
                'business_id' => $data['business_id'],
                'taxpayer_id' => $data['business']['taxpayer']['id'],
                'from_tax_type_id' => $data['tax_type_id'],
                'to_tax_type_id' => $this->new_tax_type_id,
                'reason' => $this->reason,
                'from_tax_type_currency' => $data['currency'],
                'to_tax_type_currency' => $currency,
                'category' => 'qualified',
            ];

            $taxTypeChange = BusinessTaxTypeChange::query()->create($payload);

            DB::commit();
            $this->flash('success', 'Tax type changed successfully');
            return redirect()->route('business.upgrade-tax-types.index');
        }
        catch (\Throwable $exception)
        {
            DB::rollBack();
            Log::error($exception);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
            redirect()->back()->getTargetUrl();
        }
    }

    public function render()
    {
        return view('livewire.upgrade-tax-type.create');
    }
}
