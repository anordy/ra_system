<?php

namespace App\Http\Livewire\Debt\Offence;

use App\Enum\CustomMessage;
use App\Models\Currency;
use App\Models\TaxType;
use App\Traits\CustomAlert;
use App\Traits\OffencePaymentTrait;
use App\Traits\PaymentsTrait;
use App\Traits\WorkflowProcesssingTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App\Models\Offence\Offence;

class CreateOffence extends Component
{
    use CustomAlert, WorkflowProcesssingTrait, PaymentsTrait,OffencePaymentTrait;
    public $taxType, $currency;
    public $taxTypes, $currencies = [];
    public $name, $amount,$mobile;

    protected $rules = [
        'name' => 'required|strip_tag',
        'amount' => 'required|strip_tag|numeric',
        'taxType' => 'required|strip_tag',
        'currency' => 'required|strip_tag',
        'mobile' =>'required|strip_tag|regex:/^([0-9\s\-\+\(\)]*)$/|min:10'
    ];

    protected function rules(){
        return [
            'name' => 'required|strip_tag',
            'amount' => 'required|strip_tag|numeric',
            'taxType' => 'required|strip_tag',
            'currency' => 'required|strip_tag',
            'mobile' =>'required|strip_tag|regex:/^([0-9\s\-\+\(\)]*)$/|min:10'
        ];
    }

    public function mount()
    {
        try {
            $this->taxTypes = TaxType::select('id', 'name', 'code')->get();
            $this->currencies = Currency::select('id', 'name', 'iso')->get();
        } catch (\Exception $exception){
            Log::info('OFFENCE',['MESSAGE'=>$exception->getMessage(),'TRACE'=>$exception->getTrace()]);
            abort(500, CustomMessage::error());
        }
    }

    public function submit(){
        $this->validate();
        DB::beginTransaction();
        try {
            $save = Offence::create([
                'name' => $this->name,
                'amount'=> $this->amount,
                'currency'=> $this->currency,
                'tax_type'=>$this->taxType,
                'mobile'=>$this->mobile,
                'status'=> Offence::CONTROL_NUMBER
            ]);

            $billItems = [
                [
                    'Name' => $save->name,
                    'gfs_code' => TaxType::where('code', TaxType::INVESTIGATION)->firstOrFail()->gfs_code,
                    'amount' => $save->amount,
                    'currency' => $save->currency,
                    'tax_type_id'=> $save->tax_type
                ]
            ];

//            dd($save,$billItems);
            if($save){
                $response = $this->offenceGenerateControlNo($save,$billItems);
                if ($response){
                    session()->flash('success', 'Your request was submitted, you will receive your payment information shortly.');
                    return redirect(request()->header('Referer'));
                }
                DB::commit();
                $this->customAlert('success',' Success create offence');
                $this->redirectRoute('debts.offence.index');
            }else{
                DB::rollBack();
                $this->customAlert('error',' Failure to create offence');
                $this->redirectRoute('debts.offence.index');
            }
        }catch (\Exception $e){
            DB::rollBack();
            Log::error('OFFENCE',['MESSAGE'=>$e->getMessage()]);
            $this->customAlert('error',' Failure to create offence');
            $this->redirectRoute('debts.offence.index');

        }
    }

    public function render()
    {
        return view('livewire.offence.create-offence');
    }
}
