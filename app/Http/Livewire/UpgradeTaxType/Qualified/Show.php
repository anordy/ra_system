<?php

namespace App\Http\Livewire\UpgradeTaxType\Qualified;

use App\Enum\SubVatConstant;
use App\Models\BusinessTaxTypeChange;
use App\Models\BusinessTaxTypeUpgrade;
use App\Models\Currency;
use App\Models\Returns\Vat\SubVat;
use App\Models\TaxType;
use App\Traits\CustomAlert;
use App\Traits\UpgradeTaxTypeTrait;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Show extends Component
{
    use CustomAlert, WorkflowProcesssingTrait, UpgradeTaxTypeTrait;

    public $return;
    public $sales;
    public $currency;
    public $effective_date;
    public $currencies;
    public $checkFiling;
    public $min;
    public $max;

    public $new_tax_type_id;
    public $subVats = [];
    public $sub_vat_id, $tax_type;
    public $taxTypeUpgrade, $taxTypeChange, $comments, $comment;

    public function mount($return, $sales, $currency)
    {
        $this->return = $return;
        $this->sales = $sales;
        $this->currency = $currency;

        $this->currencies = Currency::all();
        $this->checkFiling = $this->checkPreviousFiling($this->return['business_id'], $this->return['taxtype']['code']);
        $month = $this->currentFinancialMonth()->due_date;
        if (date('Y-m-d', strtotime($month)) <= date('Y-m-d')) {
            $this->min = Carbon::create(date('Y-m-d'))->addDays(1);
            $this->max = Carbon::create(date('Y-m-d'))->addDays(6);
        } else {
            $this->min = Carbon::create($month)->addDays(1);
            $this->max = Carbon::create($month)->addDays(6);
        }
        $this->min = date('Y-m-d', strtotime($this->min));
        $this->max = date('Y-m-d', strtotime($this->max));

        if ($this->return['taxtype']['code'] === TaxType::STAMP_DUTY) {
            $this->subVats = SubVat::select('id', 'name', 'code')->get();
        }

        $this->taxTypeChange = BusinessTaxTypeChange::query()
            ->where('business_id', $this->return['business_id'])
            ->where('taxpayer_id', $this->return['business']['taxpayer']['id'])
            ->where('from_tax_type_id', $this->return['tax_type_id'])
            ->first();

        if ($this->taxTypeChange) {
            $this->taxTypeUpgrade = BusinessTaxTypeUpgrade::where('business_tax_type_change_id', $this->taxTypeChange->id)->first();

            if ($this->taxTypeUpgrade) {
                $this->registerWorkflow(BusinessTaxTypeUpgrade::class, $this->taxTypeUpgrade->id);
            }

            $this->currency = $this->taxTypeChange->to_tax_type_currency;
            $this->effective_date = $this->taxTypeChange->effective_date ? Carbon::create($this->taxTypeChange->effective_date)->format('Y-m-d') : null;
            $this->sub_vat_id = $this->taxTypeChange->to_sub_vat_id;
            $this->comments = $this->taxTypeChange->reason;
        }

    }

    public function approve($transition)
    {
        $transition = $transition['data']['transition'];

        if ($this->checkTransition('registration_officer_review')) {
            if (!$this->sub_vat_id && $this->return['taxtype']['code'] === TaxType::STAMP_DUTY) {
                $this->rules['sub_vat_id'] = 'required';
                $this->rules['comments'] = 'required';
            }

            $this->validate();

            DB::beginTransaction();
            try {

                if ($this->return['taxtype']['code'] === TaxType::HOTEL) {
                    $this->tax_type = TaxType::query()->where('code', TaxType::VAT)->first();
                    if (is_null($this->tax_type)) {
                        abort(404);
                    }
                    $this->new_tax_type_id = $this->tax_type->id;
                    $subVat = SubVat::where('code', SubVatConstant::HOTELSERVICES)->first();

                    if (!$subVat) {
                        $this->customAlert('warning', 'Hotel Service Vat Category is missing');
                        return;
                    }

                    $this->sub_vat_id = $subVat->id;
                }

                if ($this->return['taxtype']['code'] === TaxType::STAMP_DUTY) {
                    $this->tax_type = TaxType::query()->where('code', TaxType::VAT)->first();
                    if (is_null($this->tax_type)) {
                        abort(404);
                    }
                    $this->new_tax_type_id = $this->tax_type->id;

                    if (!$this->sub_vat_id) {
                        $this->customAlert('warning', 'Please add sub vat category');
                        return;
                    }
                }

                if ($this->return['taxtype']['code'] === TaxType::LUMPSUM_PAYMENT) {
                    $this->tax_type = TaxType::query()->where('code', TaxType::STAMP_DUTY)->first();
                    if (is_null($this->tax_type)) {
                        abort(404);
                    }
                    $this->new_tax_type_id = $this->tax_type->id;
                }

                $payload = [
                    'to_tax_type_id' => $this->new_tax_type_id,
                    'to_sub_vat_id' => $this->sub_vat_id,
                    'reason' => $this->comments,
                    'effective_date' => $this->effective_date,
                    'status' => 'pending',
                    'to_tax_type_currency' => $this->currency,
                    'category' => 'qualified',
                ];

                $this->taxTypeChange->update($payload);

                $this->doTransition($transition, ['status' => 'agree', 'comments' => 'approved']);

                DB::commit();
                $this->flash('success', 'Forwarded successfully', [], redirect()->back()->getTargetUrl());
            } catch (\Throwable $exception) {
                DB::rollBack();
                Log::error($exception);
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
                return;
            }

        }

        if ($this->checkTransition('registration_manager_review')) {

            DB::beginTransaction();
            try {
                $this->taxTypeChange->update([
                    'status' => 'approved',
                    'approved_on' => now(),
                ]);
                $this->doTransition($transition, ['status' => 'agree', 'comments' => $this->comment]);
                DB::commit();
                $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
            } catch (Exception $exception) {
                DB::rollBack();
                Log::error($exception);
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
                return;
            }

        }

    }

    protected $rules = [
        'currency' => 'required|strip_tag',
        'effective_date' => 'required',
    ];

    protected $messages = [
        'currency.required' => 'This field is required',
        'effective_date.required' => 'This field is required',
        'sub_vat_id.required' => 'This field is required',
    ];

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];

        $this->validate(['comment' => 'required|strip_tag']);

        try {
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comment]);
            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
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
        return view('livewire.upgrade-tax-type.qualified.show');
    }
}
