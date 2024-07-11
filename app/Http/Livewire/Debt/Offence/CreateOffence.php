<?php

namespace App\Http\Livewire\Debt\Offence;

use App\Enum\CustomMessage;
use App\Models\Taxpayer;
use App\Models\Currency;
use App\Models\TaxType;
use App\Traits\CustomAlert;
use App\Traits\OffencePaymentTrait;
use App\Traits\PaymentsTrait;
use App\Traits\WorkflowProcesssingTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App\Models\Offence\Offence;
use Illuminate\Support\Facades\DB;

class CreateOffence extends Component
{
    use CustomAlert, PaymentsTrait,OffencePaymentTrait;

    public $taxType, $currency;
    public $taxTypes, $currencies = [];
    public $name, $amount, $mobile;
    public $hasZnumber;
    public $znumber;
    public $readonlyFields = true;

    protected $rules = [
//        'znumber' => 'required',
        'name' => 'required|strip_tag',
        'amount' => 'required|numeric|min:1',
        'taxType' => 'required',
        'currency' => 'required',
        'mobile' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
    ];

    public function mount()
    {
        try {
            $this->taxTypes = TaxType::select('id', 'name', 'code')->where('category','main')->get();
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
            $this->reset(['znumber', 'readonlyFields','name','mobile']);
        } else {
            $this->reset(['znumber', 'readonlyFields','name','mobile']);
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
                $this->addError('znumber', 'taxpayer not found with this Znumber: '.$this->znumber);
                $this->reset(['znumber', 'name','mobile']);
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
            ]);

            $billItems = [
                [
                    'Name' => $offence->name,
                    'gfs_code' => TaxType::findOrFail($offence->tax_type)->gfs_code,
                    'amount' => $offence->amount,
                    'currency' => $offence->currency,
                    'tax_type_id'=> $offence->tax_type
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
            $response = $this->offenceGenerateControlNo($offence,$billItems);
            if ($response){
                session()->flash('success', 'Your request was submitted, you will receive your payment information shortly.');
                return redirect(request()->header('Referer'));
            }

            $this->customAlert('success',' Success create offence');
            $this->redirectRoute('debts.offence.index');
        } catch (\Exception $exception){
            Log::error('OFFENCE',['MESSAGE'=>$exception->getMessage()]);
            $this->customAlert('error',' Failure to generate Control number');
            $this->redirectRoute('debts.offence.index');
        }
    }

    public function render()
    {
        return view('livewire.offence.create-offence');
    }
}
