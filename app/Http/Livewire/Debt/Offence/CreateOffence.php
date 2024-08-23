<?php

namespace App\Http\Livewire\Debt\Offence;

use App\Enum\CustomMessage;
use App\Models\Currency;
use App\Models\Offence\Offence;
use App\Models\Returns\Vat\SubVat;
use App\Models\Taxpayer;
use App\Models\TaxType;
use App\Traits\CustomAlert;
use App\Traits\OffencePaymentTrait;
use App\Traits\PaymentsTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CreateOffence extends Component
{
    use CustomAlert, PaymentsTrait, OffencePaymentTrait;

    public $taxType, $currency;
    public $taxTypes, $currencies = [], $subVats = [], $sub_vat_id;
    public $name, $amount, $mobile;
    public $hasZnumber;
    public $znumber;
    public $readonlyFields = true;

    protected $rules = [
        'name' => 'required|strip_tag',
        'amount' => 'required|numeric|min:1',
        'taxType' => 'required',
        'currency' => 'required',
        'mobile' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        'sub_vat_id' => 'nullable|numeric'
    ];

    public function mount()
    {
        try {
            $this->taxTypes = TaxType::select('id', 'name', 'code')
                ->where('category', 'main')
                ->whereNotIn('code', [TaxType::AIRPORT_SERVICE_SAFETY_FEE, TaxType::SEAPORT_SERVICE_TRANSPORT_CHARGE])
                ->orwhereIn('code', [TaxType::AIRPORT_SERVICE_CHARGE, TaxType::SEAPORT_SERVICE_CHARGE])
                ->orderBy('name', 'ASC')
                ->get();
            $this->currencies = Currency::select('id', 'name', 'iso')->get();
        } catch (\Exception $exception) {
            Log::error('OFFENCE', ['MESSAGE' => $exception->getMessage(), 'TRACE' => $exception->getTrace()]);
            abort(500, CustomMessage::error());
        }
    }

    public function updatedHasZnumber()
    {
        if ($this->hasZnumber == 'yes') {
            $this->readonlyFields = false;
            $this->reset(['znumber', 'readonlyFields', 'name', 'mobile']);
        } else {
            $this->reset(['znumber', 'readonlyFields', 'name', 'mobile']);
        }
    }

    public function updatedTaxType()
    {
        $taxType = TaxType::find($this->taxType, ['id', 'code']);

        if ($taxType && $taxType->code === TaxType::VAT) {
            $this->subVats = SubVat::select('id', 'name')->get();
        } else {
            $this->subVats = [];
        }
    }

    public function fetchBusinessDetails()
    {
        $this->validate([
            'znumber' => 'required',
        ]);

        try {
            $taxpayer = Taxpayer::where('reference_no', $this->znumber)->first();

            if ($taxpayer) {
                $this->name = $taxpayer->first_name . ' ' . $taxpayer->last_name;
                $this->mobile = $taxpayer->mobile;
                // Populate other fields as needed
            } else {
                $this->addError('znumber', 'taxpayer not found with this Znumber: ' . $this->znumber);
                $this->reset(['znumber', 'name', 'mobile']);
            }
        } catch (\Exception $e) {
            Log::error('FETCH_BUSINESS', ['MESSAGE' => $e->getMessage()]);
            $this->addError('znumber', 'Failed to fetch taxpayer details. Please try again.');
        }
    }

    public function submit()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            
            $offence = Offence::create([
                'name' => $this->name,
                'amount' => $this->amount,
                'currency' => $this->currency,
                'tax_type' => $this->taxType,
                'mobile' => $this->mobile,
                'status' => Offence::CONTROL_NUMBER,
                'sub_vat_id' => $this->sub_vat_id
            ]);

            if (!$offence) throw new \Exception('Failed to save offence');

            $taxType = TaxType::findOrFail($this->taxType, ['id', 'code', 'gfs_code']);

            if ($taxType->code === TaxType::VAT) {
                $taxType = SubVat::findOrFail($this->sub_vat_id, ['id', 'gfs_code']);
            }

            $billItems = [
                [
                    'Name' => $offence->name,
                    'gfs_code' => $taxType->gfs_code,
                    'amount' => $offence->amount,
                    'currency' => $offence->currency,
                    'tax_type_id' => $offence->tax_type
                ]
            ];

            Log::error('OFFENCE', ['MESSAGE' => 'BILL']);

            DB::commit();

            $this->customAlert('success', 'Offence created successfully.');

            // $this->redirectRoute('debts.offence.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('OFFENCE', ['MESSAGE' => $e->getMessage()]);
            $this->customAlert('error', 'Failed to create offence. Please try again later.');
            $this->redirectRoute('debts.offence.index');
            return;
        }

        try {
            $response = $this->offenceGenerateControlNo($offence, $billItems);
            if ($response) {
                session()->flash('success', 'Your request was submitted, you will receive your payment information shortly.');
                return redirect(request()->header('Referer'));
            }

            $this->customAlert('success', ' Success create offence');
            $this->redirectRoute('debts.offence.index');
        } catch (\Exception $exception) {
            Log::error('OFFENCE', ['MESSAGE' => $exception->getMessage()]);
            $this->customAlert('error', ' Failure to generate Control number');
            $this->redirectRoute('debts.offence.index');
        }
    }

    public function render()
    {
        return view('livewire.offence.create-offence');
    }
}
