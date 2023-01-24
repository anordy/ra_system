<?php

namespace App\Http\Livewire\UpgradeTaxType;

use App\Models\BusinessLocation;
use App\Models\BusinessTaxTypeChange;
use App\Models\Currency;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\TaxType;
use App\Traits\UpgradeTaxTypeTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class CreateModal extends Component
{
    use LivewireAlert, UpgradeTaxTypeTrait;
    public $effective_date;
    public $currency;
    public $return;
    public $currencies;
    public $comment = 'reached maximum turnover';
    public $checkFiling;
    public $min;
    public $max;

    public function mount($return)
    {
        $this->currencies = Currency::all();
        $this->return = $return;
        $this->checkFiling = $this->checkPreviousFiling($this->return['business_id'], $this->return['taxtype']['code']);
        $month = $this->currentFinancialMonth()->due_date;
        if (date('Y-m-d', strtotime($month)) <= date('Y-m-d'))
        {
            $this->min = Carbon::create(date('Y-m-d'))->addDays(1);
            $this->max = Carbon::create(date('Y-m-d'))->addDays(6);
        }
        else
        {
            $this->min = Carbon::create($month)->addDays(1);
            $this->max = Carbon::create($month)->addDays(6);
        }
        $this->min = date('Y-m-d', strtotime($this->min));
        $this->max = date('Y-m-d', strtotime($this->max));

    }

    protected $rules = [
        'currency' => 'required|strip_tag',
        'effective_date' => 'required|strip_tag',
    ];
    protected $messages = [
        'currency.required' => 'This field is required',
        'effective_date.required' => 'This field is required',
    ];


    public function submit()
    {
        $this->validate();

        DB::beginTransaction();
        try {

            if ($this->return['taxtype']['code'] === TaxType::HOTEL or $this->return['taxtype']['code'] === TaxType::STAMP_DUTY) {
                $this->tax_type = TaxType::query()
                    ->where('code', TaxType::VAT)->first();
                $this->new_tax_type_id = $this->tax_type->id;
            }

            if ($this->return['taxtype']['code'] === TaxType::LUMPSUM_PAYMENT) {
                $this->tax_type = TaxType::query()
                    ->where('code', TaxType::STAMP_DUTY)->first();
                $this->new_tax_type_id = $this->tax_type->id;
            }

            $payload = [
                'business_id' => $this->return['business_id'],
                'taxpayer_id' => $this->return['business']['taxpayer']['id'],
                'from_tax_type_id' => $this->return['tax_type_id'],
                'to_tax_type_id' => $this->new_tax_type_id,
                'reason' => $this->comment,
                'effective_date'=> $this->effective_date,
                'status'=>'approved',
                'approved_on'=>now(),
                'from_tax_type_currency' => $this->return['currency'],
                'to_tax_type_currency' => $this->currency,
                'category' => 'qualified',
            ];

            $taxTypeChange = BusinessTaxTypeChange::query()->create($payload);

            DB::commit();
            $this->flash('success', 'Tax type changed successfully');
            return redirect()->route('business.qualified-tax-types.index');
        }

        catch (\Throwable $exception)
        {
            DB::rollBack();
            Log::error($exception);
            $this->alert('error', 'Something went wrong, please contact the administrator for help!!!');
            redirect()->back()->getTargetUrl();
        }
    }

    public function render()
    {
        return view('livewire.upgrade-tax-type.create-modal');
    }
}
