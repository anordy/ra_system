<?php

namespace App\Http\Livewire\Returns\Hotel;

use Exception;
use App\Models\TaxType;
use Livewire\Component;
use App\Models\Taxpayer;
use App\Traits\PenaltyTrait;
use App\Models\BusinessTaxType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Returns\ReturnStatus;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\HotelReturns\HotelReturnConfig;
use App\Models\Returns\HotelReturns\HotelReturnPenalty;
use App\Traits\PaymentsTrait;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;


class Adjust extends Component
{
    use LivewireAlert, PenaltyTrait, PaymentsTrait;
    // Route parameters
    public $return_type;
    public $business_location_id;
    public $tax_type_id;
    public $return_id;
    public $return;
    public $taxTypeCurrency;

    // Data options
    public $business;
    public $business_tax;
    public $currency;
    public $currentHotelLevyConfigs;
    public $fillingMonth;

    // Main return properties
    public $hotelLevyConfigs;
    public $configs = [];
    public $total;
    public $infrastructure_tax = 0;
    public $total_pax;
    public $penalties = [];
    public $submitted = false;
    public $total_amount_due_with_penalty = 0;
    public $interest = 0;
    public $penalty = 0;
    public $taxType;


    public function mount($return_id)
    {
        $this->return_id = $return_id;
        $this->return = HotelReturn::findOrFail($this->return_id);
        $this->fillingMonth = $this->return->financialMonth;
        $this->tax_type_id = $this->return->tax_type_id;
        $this->taxType = $this->return->taxtype;
        $this->taxTypes = TaxType::all();
        $this->taxTypeCurrency = BusinessTaxType::where('business_id', $this->return->business_id)->where('tax_type_id', $this->return->tax_type_id)->value('currency');
        $this->hotelLevyConfigs = HotelReturnConfig::orderBy('order')->get()->toArray();

        $this->currentHotelLevyConfigs = $this->return->items;

        $data = collect();
        foreach ($this->hotelLevyConfigs as $config) {
            $current_values = $this->currentHotelLevyConfigs->where('config_id', $config['id'])->first();

            $config['value'] = $current_values->value ?? 0;
            $config['vat'] = $current_values->vat ?? 0;
            $data->push($config);
        }
        $this->penalties = $this->getTotalPenalties($this->return->financialMonth, $this->return->total_amount_due, $this->taxTypeCurrency);
        $this->configs = $data;
    }

    protected $rules = [
        'configs.*.value' => 'required|numeric',
    ];

    protected $messages = [
        'configs.*.value.required' => 'Value is required.',
    ];

    protected function singleVatCalculate($key)
    {
        $config = $this->configs[$key];
        if (is_numeric($config['value']) && $config['rate_applicable']) {
            if ($config['rate_type'] == 'fixed') {
                if ($this->taxTypeCurrency == 'TZS') {
                    if ($config['currency'] == 'USD') {
                        $config['vat'] = $config['value'] * $config['rate_usd'] * 2300;
                    } else {
                        $config['vat'] = $config['value'] * $config['rate'];
                    }
                } else {
                    if ($config['currency'] == 'USD') {
                        $config['vat'] = $config['value'] * $config['rate_usd'];
                    } else {
                        $config['vat'] = $config['value'] * $config['rate'];
                    }
                }
                $this->configs[$key] = $config;
                return $config['vat'];
            } else {
                $config['vat'] = ($config['value'] * $config['rate']) / 100;
                $this->configs[$key] = $config;
                return $config['vat'];
            }
        } else if ($config['code'] == 'LW') {
            $config['vat'] = $config['value'];
            $this->configs[$key] = $config;
            return $config['vat'];
        } else {
            $config['vat'] = 0;
            $this->configs[$key] = $config;
            return 0;
        }
    }

    public function totalVatCalculate($formular, $key)
    {
        $configs = collect($this->configs);

        $formulaExpression = $formular;

        foreach ($configs as $config) {
            $value = strval($config['vat']);
            $formulaExpression = str_replace($config['code'], $value, $formulaExpression);
        }
        $expressionLanguage = new ExpressionLanguage();
        $data = $expressionLanguage->evaluate($formulaExpression);
        $config = $this->configs[$key];
        $config['vat'] = $data;
        $this->configs[$key] = $config;
        $this->total = $data;
        return $data;
    }

    public function valueCalculated($key, $formular)
    {
        if ($formular == '') return 0;
        $configs = collect($this->configs);
        $formulaExpression = $formular;

        foreach ($configs as $config) {
            $configValue = $config['value'];

            if (!is_numeric($configValue)) {
                $configValue = 0;
            }

            $value = strval($configValue);
            $formulaExpression = str_replace($config['code'], $value, $formulaExpression);
        }

        $expressionLanguage = new ExpressionLanguage();
        $data = $expressionLanguage->evaluate($formulaExpression);
        $config = $this->configs[$key];
        $config['value'] = $data;
        $this->configs[$key] = $config;
        return $data;
    }

    public function submit()
    {
        if ($this->tax_type_id === 2) {
            // Hotel Levy
            foreach ($this->configs as $key => $con) {
                if ($con['code'] == 'TOTAL_HL') {
                    $this->total = $this->totalVatCalculate($con['formular'], $key);
                    $con['value'] = $this->total;
                }

                if ($con['code'] == 'IT') {
                    $this->infrastructure_tax = $con['vat'];
                }
            }
        } else if ($this->tax_type_id === 4) {
            // Tour operation
            foreach ($this->configs as $key => $con) {
                if ($con['code'] == 'TOTAL_TOS') {
                    $this->total = $this->totalVatCalculate($con['formular'], $key);
                    $con['value'] = $this->total;
                }
            }
        } else if ($this->tax_type_id === 3) {
            // Restaurant
            foreach ($this->configs as $key => $con) {
                if ($con['code'] == 'TOTAL_RL') {
                    $this->total = $this->totalVatCalculate($con['formular'], $key);
                    $con['value'] = $this->total;
                }
            }
        }

        DB::beginTransaction();
        try {
            $edit_count = $this->return->edited_count + 1;

            $diffInTotalAmount = $this->total - $this->return->total_amount_due;
            // dd($diffInTotalAmount);

            // store penalty amount in this table
            $payload = [
                'filled_type' => Taxpayer::class,
                'filled_id' => auth()->user()->id,
                'edited_count' => $edit_count,
                'total_amount_due' => $this->total,
                'total_amount_due_with_penalty' => $this->total + $this->penalty + $this->interest,
                'interest' => $this->interest,
                'penalty' => $this->penalty,
                'hotel_infrastructure_tax' => $this->infrastructure_tax,
                'status' => ReturnStatus::SUBMITTED,
            ];

            $this->return->update($payload);

            $penaltyArr = $this->getPenaltArrays($this->fillingMonth, $this->total, $this->taxTypeCurrency);

            if(count($this->return->penalties) > 0){
            // dd($this->return->penalties);

                $this->return->penalties()->delete();
                // dd('dhjd');

                if (count($penaltyArr) > 0) {
                    foreach($penaltyArr as $penaltItem){
                        HotelReturnPenalty::create([
                            'return_id' => $this->return->id,
                            'financial_month_name' => $penaltItem['returnMonth'],
                            'tax_amount' => $penaltItem['taxAmount'],
                            'late_filing' => $penaltItem['lateFilingAmount'],
                            'late_payment' => $penaltItem['latePaymentAmount'],
                            'rate_percentage' => $penaltItem['interestRate'],
                            'rate_amount' => $penaltItem['interestAmount'],
                            'penalty_amount' => $penaltItem['penaltyAmount'],
                        ]);
        
                    }
                }
         
            }


            // Create control number
            // Hotel levy must have infrastructure tax
            if ($this->tax_type_id === 2) {
                $billItems = [
                    [
                        'billable_id' => $this->return->id,
                        'billable_type' => get_class($this->return),
                        'use_item_ref_on_pay' => 'N',
                        'amount' => $this->total,
                        'currency' => $this->taxTypeCurrency,
                        'gfs_code' => $this->taxType->gfs_code,
                        'tax_type_id' => $this->taxType->id
                    ],
                    [
                        'billable_id' => $this->return->id,
                        'billable_type' => get_class($this->return),
                        'use_item_ref_on_pay' => 'N',
                        'amount' => $this->infrastructure_tax,
                        'currency' => $this->taxTypeCurrency,
                        'gfs_code' => $this->taxType->gfs_code,
                        'tax_type_id' => $this->taxTypes->where('code', 'infrastructure')->first()->id
                    ],
                ];
            } else {
                // Create bill for Tour Operation Service OR Restaurant Levy
                $billItems = [
                    [
                        'billable_id' => $this->return->id,
                        'billable_type' => get_class($this->return),
                        'use_item_ref_on_pay' => 'N',
                        'amount' => $this->total,
                        'currency' => $this->taxTypeCurrency,
                        'gfs_code' => $this->taxType->gfs_code,
                        'tax_type_id' => $this->taxType->id
                    ],
                ];
            }

            if ($this->penalty > 0) {
                $billItems[] = [
                    'billable_id' => $this->return->id,
                    'billable_type' => get_class($this->return),
                    'use_item_ref_on_pay' => 'N',
                    'amount' => $this->penalty,
                    'currency' => $this->taxTypeCurrency,
                    'gfs_code' => $this->taxType->gfs_code,
                    'tax_type_id' => $this->taxTypes->where('code', 'penalty')->first()->id
                ];
            }

            if ($this->interest > 0) {
                $billItems[] = [
                    'billable_id' => $this->return->id,
                    'billable_type' => get_class($this->return),
                    'use_item_ref_on_pay' => 'N',
                    'amount' => $this->interest,
                    'currency' => $this->taxTypeCurrency,
                    'gfs_code' => $this->taxType->gfs_code,
                    'tax_type_id' => $this->taxTypes->where('code', 'interest')->first()->id
                ];
            }

            // Generate control number
            $this->generateControlNo($this->return, $billItems);

            // Insert config returns
            // Hotel Levy
            if ($this->tax_type_id === 2) {
                $values = [];
                foreach ($this->configs as $con) {
                    if ($con['tax_type_id'] == $this->tax_type_id || $con['tax_type_id'] === null) {
                        $values[] = [
                            'config_id' => $con['id'],
                            'value' => $con['value'],
                            'vat' => $con['vat'],
                        ];
                    }
                }
                $this->return->items()->delete();
                $this->return->items()->createMany($values);
            } else if ($this->tax_type_id === 4) {
                // Tour operation
                $values = [];
                foreach ($this->configs as $con) {
                    if ($con['tax_type_id'] == $this->tax_type_id  || $con['tax_type_id'] === null) {
                        $values[] = [
                            'config_id' => $con['id'],
                            'value' => $con['value'],
                            'vat' => $con['vat'],
                        ];
                    }
                }
                $this->return->items()->delete();
                $this->return->items()->createMany($values);
            } else if ($this->tax_type_id === 3) {
                // Restaurant
                $values = [];
                foreach ($this->configs as $con) {
                    if ($con['tax_type_id'] == $this->tax_type_id  || $con['tax_type_id'] === null) {
                        $values[] = [
                            'config_id' => $con['id'],
                            'value' => $con['value'],
                            'vat' => $con['vat'],
                        ];
                    }
                }
                $this->return->items()->delete();
                $this->return->items()->createMany($values);
            }

            DB::commit();
            session()->flash('success', 'Return has been updated!');
            $this->redirect(route('returns.hotel.show', encrypt($this->return->id)));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function toggleSummary($value){
        $this->validate();
        $this->submitted = $value;
    }

    public function render()
    {
        
        if($this->submitted){
            if($financialMonth = $this->getFilingMonth($this->return->business_location_id, HotelReturn::class)){
                $this->penalties = $this->getTotalPenalties($financialMonth, $this->total, $this->taxTypeCurrency);
                $totals = $this->getTotals($financialMonth, $this->total, $this->taxTypeCurrency);
                $this->penalty = $totals['penalty'];
                $this->interest = $totals['interest'];
            } else {
                $this->penalties = [];
            }
            $actionsView = 'livewire.returns.hotel.includes.edit-actions';
            return view('livewire.returns.penalty.penalty-summary', ['penalties' => $this->penalties, 'actionsView' => $actionsView]);
        }
        $actionsView = 'livewire.returns.hotel.includes.edit-actions';
        return view('livewire.returns.hotel.adjust', compact('actionsView'));
    }
}
