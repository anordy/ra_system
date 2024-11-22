<?php

namespace App\Http\Livewire\Business\TaxType;

use App\Enum\CustomMessage;
use App\Enum\GeneralConstant;
use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\BusinessLocation;
use App\Models\BusinessStatus;
use App\Models\BusinessTaxType;
use App\Models\BusinessTaxTypeChange;
use App\Models\LumpSumPayment;
use App\Models\Returns\LumpSum\LumpSumConfig;
use App\Models\Returns\Vat\SubVat;
use App\Models\TaxType;
use App\Traits\CustomAlert;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;


class TaxTypeChangeApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, CustomAlert;

    public $modelId;
    public $modelName;
    public $comments;
    public $taxchange;
    public $taxTypes;
    public $from_tax_type_id;
    public $to_tax_type_id;
    public $to_tax_type_currency;
    public $effective_date;
    public $today;
    public $subVatOptions = [], $annualSales = [];
    public $showSubVatOptions = false;
    public $sub_vat_id;
    public $showLumpsumOptions = false, $lumpSumAnnualSaleId, $selectedAnnualSale = [];

    public function mount($modelName, $modelId)
    {
        try {
            $this->modelName = $modelName;
            $this->modelId = decrypt($modelId);
            $this->registerWorkflow($modelName, $this->modelId);
            $this->taxchange = BusinessTaxTypeChange::find($this->modelId);
            if (is_null($this->taxchange)) {
                abort(404);
            }
            $this->to_tax_type_id = $this->taxchange->to_tax_type_id;
            $this->from_tax_type_id = $this->taxchange->from_tax_type_id;
            $this->to_tax_type_currency = $this->taxchange->to_tax_type_currency;
            $this->taxTypes = TaxType::select('id', 'name')->where('category', 'main')->get();
            $this->today = Carbon::today()->addDay()->format('Y-m-d');

            if (isset($this->taxchange->toTax->code) && $this->taxchange->toTax->code === TaxType::VAT) {
                $this->subVatOptions = SubVat::query()->select('id', 'name', 'code')->get();
                $this->showSubVatOptions = true;
            } else if (isset($this->taxchange->toTax->code) && $this->taxchange->toTax->code === TaxType::LUMPSUM_PAYMENT) {
                $this->annualSales = LumpSumConfig::select('id', 'min_sales_per_year', 'max_sales_per_year', 'payments_per_year', 'payments_per_installment')->get();
                $this->showLumpsumOptions = true;
                $this->showSubVatOptions = false;
                $estimate = LumpSumPayment::where('business_id', $this->subject->business_id)->select('annual_estimate')->first();
                $this->selectedAnnualSale = [
                    'annual_estimate' => number_format($estimate->annual_estimate ?? 0, 2),
                    'quarter_estimate' => number_format(($estimate->annual_estimate ?? 0) / GeneralConstant::FOUR_INT)
                ];
            }
        } catch (Exception $exception) {
            Log::error('TAX-TYPE-CHANGE-APPROVAL-MOUNT', [$exception]);
            abort(500, 'Something went wrong, please contact your system administrator for support.');
        }

    }

    public function updated($property)
    {
        if ($property === 'to_tax_type_id') {
            $taxType = TaxType::findOrFail($this->to_tax_type_id, ['code']);
            if ($taxType->code == TaxType::VAT) {
                $this->subVatOptions = SubVat::all();
                $this->showSubVatOptions = true;
                $this->showLumpsumOptions = false;
            } else if ($taxType->code == TaxType::LUMPSUM_PAYMENT) {
                $this->annualSales = LumpSumConfig::select('id', 'min_sales_per_year', 'max_sales_per_year', 'payments_per_year', 'payments_per_installment')->get();
                $this->showLumpsumOptions = true;
                $this->showSubVatOptions = false;
            } else {
                $this->showSubVatOptions = false;
                $this->showLumpsumOptions = false;
            }
        }

        if ($property === 'lumpSumAnnualSaleId') {
            $estimate = LumpSumConfig::findOrFail($this->lumpSumAnnualSaleId, ['payments_per_year']);
            $this->selectedAnnualSale = [
                'annual_estimate' => number_format($estimate->payments_per_year, 2),
                'quarter_estimate' => number_format(($estimate->payments_per_year ?? 0) / GeneralConstant::FOUR_INT)
            ];
        }
    }


    public function approve($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate([
            'to_tax_type_currency' => 'required|alpha',
            'to_tax_type_id' => 'required|numeric'
        ]);

        if ($this->to_tax_type_id == $this->from_tax_type_id) {
            $this->customAlert('warning', 'You cannot change to an existing tax type');
            return;
        }

        if ($this->showSubVatOptions) {
            $this->validate([
                'sub_vat_id' => 'required|integer'
            ]);
        }

        if ($this->showLumpsumOptions) {
            $this->validate([
                'lumpSumAnnualSaleId' => 'required|integer'
            ]);
        }

        DB::beginTransaction();
        try {
            if ($this->checkTransition('registration_manager_review')) {

                $this->subject->status = BusinessStatus::APPROVED;
                $this->subject->effective_date = Carbon::now()->toDateTimeString();
                $this->subject->to_sub_vat_id = $this->sub_vat_id;
                $this->subject->approved_on = Carbon::now()->toDateTimeString();

                $taxType = TaxType::findOrFail($this->to_tax_type_id, ['code']);

                if ($taxType->code === TaxType::VAT) {
                    $locations = BusinessLocation::where('business_id', $this->taxchange->business_id)->get();

                    if (count($locations) > 0) {
                        foreach ($locations as $location) {
                            if (!$location->vrn) {
                                $location->generateVrn();
                            }
                        }
                    }
                } else if ($taxType->code === TaxType::LUMPSUM_PAYMENT) {
                    $estimate = LumpSumConfig::findOrFail($this->lumpSumAnnualSaleId, ['payments_per_year']);

                    $locations = BusinessLocation::select('id')->where('business_id', $this->taxchange->business_id)->get();

                    if (count($locations) > 0) {
                        foreach ($locations as $location) {
                            $paymentExists = LumpSumPayment::query()->select('id')
                                ->where('business_location_id', $location->id)
                                ->first();

                            if ($paymentExists) {
                                if (!$paymentExists->delete()) throw new Exception('Failed to delete lumpsum payment');
                            }

                            LumpSumPayment::create([
                                'filed_by_id' => auth()->user()->id,
                                'business_id' => $this->subject->business_id,
                                'business_location_id' => $location->id,
                                'annual_estimate' => $estimate->payments_per_year,
                                'payment_quarters' => GeneralConstant::FOUR_INT,
                                'currency' => $this->subject->to_tax_type_currency,
                            ]);
                        }
                    }

                }

                $currentTaxType = BusinessTaxType::where('business_id', $this->subject->business_id)
                    ->where('tax_type_id', $this->subject->from_tax_type_id)
                    ->firstOrFail();

                $currentTaxType->tax_type_id = $this->subject->to_tax_type_id;
                $currentTaxType->sub_vat_id = $this->sub_vat_id;
                $currentTaxType->currency = $this->subject->to_tax_type_currency;

                if (!$currentTaxType->save()) throw new Exception('Failed to update tax type');

                DB::commit();

                $notification_payload = [
                    'tax_change' => $this->taxchange,
                ];

                event(new SendMail('change-tax-type-approval', $notification_payload));
                event(new SendSms('change-tax-type-approval', $notification_payload));
            }
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollback();
            Log::error('TAX-TYPE-CHANGE-APPROVE', [$e->getMessage()]);
            $this->customAlert('error', CustomMessage::error());
        }
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate(['comments' => 'required|strip_tag']);
        try {
            if ($this->checkTransition('registration_manager_reject')) {
                $this->subject->status = BusinessStatus::REJECTED;
            }
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error('TAX-TYPE-CHANGE-REJECT', [$e->getMessage()]);
            $this->customAlert('error', CustomMessage::error());
        }
    }

    protected $listeners = [
        'approve', 'reject'
    ];

    public function confirmPopUpModal($action, $transition)
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
            'data' => [
                'transition' => $transition
            ],
        ]);
    }


    public function render()
    {
        return view('livewire.approval.taxtype-change');
    }
}
