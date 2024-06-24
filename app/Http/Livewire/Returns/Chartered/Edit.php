<?php

namespace App\Http\Livewire\Returns\Chartered;

use App\Enum\ApplicationStep;
use App\Enum\CharteredType;
use App\Enum\CustomMessage;
use App\Enum\GeneralConstant;
use App\Enum\GeneralReturnConstants;
use App\Enum\ReturnApplicationStatus;
use App\Enum\ReturnCategory;
use App\Enum\VettingStatus;
use App\Models\BusinessLocation;
use App\Models\BusinessTaxType;
use App\Models\Returns\Chartered\CharteredReturn;
use App\Models\Returns\Chartered\CharteredReturnConfig;
use App\Models\Returns\Chartered\CharteredReturnItem;
use App\Models\Returns\ReturnStatus;
use App\Models\Taxpayer;
use App\Models\TaxType;
use App\Traits\CustomAlert;
use App\Traits\ExchangeRateTrait;
use App\Traits\PenaltyTrait;
use App\Traits\ReturnManualValidationTrait;
use App\Traits\TaxVerificationTrait;
use App\Traits\VerificationTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;


class Edit extends Component
{
    use CustomAlert, PenaltyTrait, TaxVerificationTrait, ReturnManualValidationTrait, ExchangeRateTrait, WithFileUploads, VerificationTrait;

    // Route parameters
    public $return_type;
    public $business_location_id;
    public $tax_type_id;
    public $filling_month_id;

    // Data options
    public $business;
    public $currency;
    public $fillingMonth;


    // Main return properties
    public $charteredConfigs;
    public $configs = [];
    public $modelName;
    public $submitted = false;
    public $taxTypeCurrency;
    public $taxTypes;
    public $taxType;
    public $total, $return;
    public $todaysExchangeRate;
    public $manifestAttachment, $companyName, $passengersType = CharteredReturn::LOCAL;


    public function mount($return_id)
    {
        $this->return = CharteredReturn::findOrFail(decrypt($return_id));
        $this->tax_type_id = $this->return->tax_type_id;
        $this->filling_month_id = $this->return->financial_month_id;
        $this->business_location_id = $this->return->business_location_id;

        $this->fillingMonth = $this->getCurrentFinancialMonth();
        $this->business = BusinessLocation::with(['business'])->findOrFail($this->business_location_id, ['id', 'business_id'])->business;
        $this->taxType = TaxType::findOrFail($this->tax_type_id, ['id', 'code', 'name', 'gfs_code']);
        $this->taxTypeCurrency = BusinessTaxType::select('currency')->where('business_id', $this->business->id)->where('tax_type_id', $this->tax_type_id)->first()->currency;
        $this->todaysExchangeRate = $this->getExchangeRate('USD');
        $this->currentConfigs = $this->return->items;

        if ($this->taxType->code == TaxType::CHARTERED_SEA) {
            $this->charteredConfigs = CharteredReturnConfig::select('id', 'financial_year_id', 'order', 'tax_type_code', 'code', 'name', 'row_type', 'value_calculated', 'col_type', 'rate_applicable', 'rate_type', 'currency', 'rate', 'rate_usd', 'value_formular', 'formular', 'active')->where('tax_type_code', $this->taxType->code)->orderBy('order')->get()->toArray();
        } else if ($this->taxType->code == TaxType::CHARTERED_FLIGHT) {
            $this->charteredConfigs = CharteredReturnConfig::select('id', 'financial_year_id', 'order', 'tax_type_code', 'code', 'name', 'row_type', 'value_calculated', 'col_type', 'rate_applicable', 'rate_type', 'currency', 'rate', 'rate_usd', 'value_formular', 'formular', 'active')->where('tax_type_code', $this->taxType->code)->orderBy('order')->get()->toArray();
        } else {
            abort(404);
        }

        $data = collect();

        foreach ($this->charteredConfigs as $config) {
            $current_values = $this->currentConfigs->where('config_id', $config['id'])->first();
            $config['value'] = $current_values->value ?? GeneralConstant::ZERO_INT;
            $config['vat'] = $current_values->vat ?? GeneralConstant::ZERO_INT;
            $data->push($config);
        }

        $this->configs = $data;
    }

    protected function rules()
    {
        $rules = [
            'configs.*.value' => 'required|regex:/^[\d+(\.\d+)\s,]*$/'
        ];

        if ($this->taxType->code === TaxType::CHARTERED_SEA) {
            $rules['manifestAttachment'] = ['required'];
            $rules['companyName'] = ['required', 'strip_tag'];
        }

        if ($this->taxType->code === TaxType::CHARTERED_FLIGHT) {
            $rules['passengersType'] = ['required'];
        }

        return $rules;

    }

    protected function initializeConfigs()
    {
        $data = collect();

        foreach ($this->charteredConfigs as $config) {
            $config['value'] = GeneralConstant::ZERO_INT;
            $config['vat'] = GeneralConstant::ZERO_INT;
            $data->push($config);
        }
        $this->configs = $data;
    }

    protected $messages = [
        'configs.*.value.required' => 'Value is required.',
        'configs.*.value.regex' => 'The value must be an integer.',
        'manifestAttachment.required' => 'Supporting attachment is required'
    ];


    protected function singleVatCalculate($key)
    {
        $config = $this->configs[$key];
        $value = str_replace(',', '', $config['value']);
        if (is_numeric($value)) {
            if ($config['rate_type'] == GeneralReturnConstants::FIXED) {
                if ($config['currency'] == 'USD') {
                    $config['vat'] = $value * $config['rate_usd'];
                } else {
                    $config['vat'] = $value * $config['rate'];
                }
                $this->configs[$key] = $config;
            } else {
                $config['vat'] = (($value * $config['rate']) * 0.01);
                if ($config['code'] === 'NSUS') {
                    $config['vat'] = roundOff($config['vat'], 'USD');
                } else if ($config['code'] === 'NSTZ') {
                    $config['vat'] = roundOff($config['vat'], 'TZS');
                }
                $this->configs[$key] = $config;
            }
            return $config['vat'] ?? GeneralConstant::ZERO_INT;
        } else {
            $config['vat'] = GeneralConstant::ZERO_INT;
            $this->configs[$key] = $config;
            return GeneralConstant::ZERO_INT;
        }
    }

    protected function totalVatCalculate($key, $formular)
    {
        $formulars = explode('_', str_replace(['+', '-', '/', '*'], '_', $formular));
        $configs = collect($this->configs);
        $codeConfigs = $configs->whereIn('code', $formulars)->all();

        $formulaExpression = $formular;

        foreach ($codeConfigs as $config) {
            $value = strval($config['vat']);
            $formulaExpression = str_replace($config['code'], $value, $formulaExpression);
        }
        $expressionLanguage = new ExpressionLanguage();
        $data = $expressionLanguage->evaluate($formulaExpression);
        $config = $this->configs[$key];
        $config['vat'] = $data;
        $this->configs[$key] = $config;

        if ($this->taxType->code == TaxType::CHARTERED_FLIGHT) {
            $this->total = $this->totalAmountOnFlight();
        } elseif ($this->taxType->code == TaxType::CHARTERED_SEA) {
            $this->total = $this->totalAmountOnSea();
        }
        return $data;
    }

    public function valueCalculated($key, $formular)
    {
        if ($formular == '') {
            return GeneralConstant::ZERO_INT;
        }

        $configs = collect($this->configs);
        $formulaExpression = $formular;

        foreach ($configs as $config) {
            $value = str_replace(',', '', $config['value']);
            $configValue = $value;

            if (!is_numeric($configValue)) {
                $configValue = GeneralConstant::ZERO_INT;
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

    public function updatedPassengersType()
    {
        $this->initializeConfigs();
    }

    public function totalAmountOnSea()
    {
        try {
            foreach ($this->configs as $config) {
                if (in_array($config['code'], ['TLSUSD'])) {
                    $tour_tax = $config['vat'];
                }
            }
            return round($tour_tax ?? GeneralConstant::ZERO_INT, 2);

        } catch (Exception $exception) {
            Log::error('RETURNS-CHARTERED-CREATE-TOTAL-AMOUNT-ON-SEA', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }

    public function totalAmountOnFlight()
    {
        try {
            if ($this->passengersType === CharteredReturn::LOCAL) {
                $code = ['TLATZS'];
            } else if ($this->passengersType === CharteredReturn::FOREIGN) {
                $code = ['TLAUSD'];
            } else {
                throw new Exception('Invalid passenger type selected');
            }
            foreach ($this->configs as $config) {
                if (in_array($config['code'], $code)) {
                    $tax = $config['vat'];
                }
            }
            return round($tax ?? GeneralConstant::ZERO_INT, 2);

        } catch (Exception $exception) {
            Log::error('RETURNS-CHARTERED-CREATE-TOTAL-AMOUNT-ON-FLIGHT', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }

    public function submit()
    {
        $charteredType = null;
        if ($this->taxType->code == TaxType::CHARTERED_FLIGHT) {
            // Hotel Levy
            $charteredType = CharteredType::FLIGHT;
        } else if ($this->taxType->code == TaxType::CHARTERED_SEA) {
            // Tour operation
            $charteredType = CharteredType::SEA;
        } else {
            $this->customAlert('error', __('No total amount found'));
            return;
        }

        try {
            DB::beginTransaction();

            $this->return->company_name = $this->companyName;
            $this->return->currency = $this->taxTypeCurrency;
            $this->return->filed_by_type = Taxpayer::class;
            $this->return->filed_by_id = auth()->user()->id;
            $this->return->curr_payment_due_date = Carbon::now()->addDays(10);
            $this->return->infrastructure_tax = GeneralConstant::ZERO_INT;
            $this->return->infrastructure_znz_tm = GeneralConstant::ZERO_INT;
            $this->return->infrastructure_znz_znz = GeneralConstant::ZERO_INT;
            $this->return->airport_service_charge = $this->totalAmountOnFlight() ?? GeneralConstant::ZERO_INT;
            $this->return->airport_safety_fee = GeneralConstant::ZERO_INT;
            $this->return->seaport_service_charge = $this->totalAmountOnSea() ?? GeneralConstant::ZERO_INT;
            $this->return->seaport_transport_charge = GeneralConstant::ZERO_INT;
            $this->return->chartered_type = $charteredType;
            $this->return->total_amount_due = $this->total ?? GeneralConstant::ZERO_INT;
            $this->return->total_amount_due_with_penalties = $this->total;
            $this->return->penalty = GeneralConstant::ZERO_INT;
            $this->return->interest = GeneralConstant::ZERO_INT;
            $this->return->status = ReturnStatus::SUBMITTED;
            $this->return->application_status = ReturnApplicationStatus::SUBMITTED;
            $this->return->return_category = ReturnCategory::NORMAL;
            $this->return->submitted_at = Carbon::now()->toDateTimeString();

            if ($this->manifestAttachment) {
                $this->return->manifest_path = $this->manifestAttachment->store('/manifest');
            }

            $this->return->save();

            if (!$this->return) {
                throw new Exception('Failed to Save Return');
            }

            $this->return->items()->delete();

            // insert chartered return items
            foreach ($this->configs as $config) {
                $item = CharteredReturnItem::query()->create([
                    'return_id' => $this->return->id,
                    'config_id' => $config['id'],
                    'value' => str_replace(',', '', $config['value']),
                    'vat' => roundOff($config['vat'], $this->taxTypeCurrency),
                ]);
                if (!$item) {
                    throw new Exception('Failed to Save Return Item');
                }
            }


            $this->return->tax_return->update([
                'filed_by_id' => $this->return->filed_by_id,
                'filed_by_type' => $this->return->filed_by_type,

                'principal' => $this->return->total_amount_due,
                'interest' => $this->return->interest,
                'penalty' => $this->return->penalty,

                'infrastructure' => $this->return->infrastructure_tax,

                'airport_safety_fee' => $this->return->airport_safety_fee,
                'airport_service_charge' => $this->return->airport_service_charge,

                'seaport_service_charge' => $this->return->seaport_service_charge,
                'seaport_transport_charge' => $this->return->seaport_transport_charge,

                'infrastructure_znz_znz' => $this->return->infrastructure_znz_znz,
                'infrastructure_znz_tz' => $this->return->infrastructure_znz_tm,

                'financial_month_id' => $this->return->financial_month_id,

                'total_amount' => $this->return->total_amount_due_with_penalties,
                'outstanding_amount' => $this->return->total_amount_due_with_penalties,

                'filing_due_date' => $this->return->curr_payment_due_date,
                'payment_due_date' => $this->return->curr_payment_due_date,

                'curr_filing_due_date' => $this->return->curr_payment_due_date,
                'curr_payment_due_date' => $this->return->curr_payment_due_date,

                'return_category' => $this->return->return_category,
                'application_step' => ApplicationStep::FILING,
                'payment_status' => $this->return->status,
                'vetting_status' => VettingStatus::CORRECTED
            ]);

            // Trigger Tax Vetting
            $this->registerWorkflow(get_class($this->return->tax_return), $this->return->tax_return->id);
            $this->doTransition('application_corrected', 'approved');

            DB::commit();

            // Sign return
            $this->sign($this->return->tax_return);

            session()->flash('success', __('Return updated successful, Please wait for approval'));
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('RETURNS-CHARTERED-EDIT-SUBMIT', [$e]);
            $this->customAlert('error', CustomMessage::error());
            return;
        }

        return redirect()->route('returns.chartered.show', encrypt($this->return->id));
    }

    protected $listeners = [
        'submit', 'cancel'
    ];

    public function confirmPopUpModal($action)
    {
        $this->customAlert('warning', 'Are you sure you want to complete this action?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Confirm',
            'onConfirmed' => $action,
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
        ]);
    }


    public function toggleSummary($value)
    {
        $this->validate();
        $this->submitted = $value;
    }

    public function render()
    {
        if ($this->submitted) {
            if ($this->taxType->code == TaxType::CHARTERED_FLIGHT) {
                // Hotel Levy
                $this->total = $this->totalAmountOnFlight();
            } else if ($this->taxType->code == TaxType::CHARTERED_SEA) {
                // Tour operation
                $this->total = $this->totalAmountOnSea();
            } else {
                $this->customAlert('error', __('No total amount found'));
                return;
            }
            $actionsView = 'livewire.returns.chartered.includes.file-actions';
            return view('livewire.returns.penalty.penalty-summary', ['penalties' => [], 'totals' => $this->totals ?? 0, 'actionsView' => $actionsView, 'infrastructure_tax' => 0, 'total' => $this->total, 'transactionFee' => 0, 'failures' => []]);
        }

        return view('livewire.returns.chartered.edit');

    }
}
