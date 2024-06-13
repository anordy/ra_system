<?php

namespace App\Http\Livewire\Approval\Mvr;

use App\Enum\Currencies;
use App\Enum\CustomMessage;
use App\Enum\PublicServiceMotorStatus;
use App\Events\SendSms;
use App\Jobs\SendCustomSMS;
use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\PublicService\PublicServicePayment;
use App\Models\PublicService\PublicServicePaymentCategory;
use App\Models\PublicService\PublicServicePaymentInterval;
use App\Models\TaxType;
use App\Traits\CustomAlert;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PublicServiceRegistrationApprovalProcessing extends Component
{
    use CustomAlert, WorkflowProcesssingTrait;

    public $modelId;
    public $modelName;
    public $comments;
    public $psPayment;
    public $psPaymentCategories = [];
    public $psPaymentCategoryId;
    public $psPaymentMonths = [];
    public $psPaymentMonthId;

    protected $rules = [
      'psPaymentMonthId'  => 'nullable'
    ];

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->registerWorkflow($modelName, $this->modelId);

        if ($this->subject->payment) {
            $this->psPayment = $this->subject->payment;
            $this->psPaymentCategoryId = $this->subject->payment->public_service_payment_category_id;
            $paymentMonth = $this->subject->payment->payment_months;
            $this->psPaymentMonthId = PublicServicePaymentInterval::query()->where('value', $paymentMonth)->first()->id;
        }

        if (strtolower($this->subject->business->category->name) == BusinessCategory::COMPANY) {
            $this->psPaymentCategories = PublicServicePaymentCategory::query()
                ->select('id', 'name', 'turnover_tax')
                ->where('name', PublicServicePaymentCategory::COMPANY)
                ->get();

            $this->psPaymentMonths = PublicServicePaymentInterval::query()
                ->where('value', PublicServicePaymentInterval::ANNUAL)
                ->select('id', 'value')
                ->get();
        } else {
            $this->psPaymentCategories = PublicServicePaymentCategory::select('id', 'name', 'turnover_tax')->get();
            $this->psPaymentMonths = PublicServicePaymentInterval::select('id', 'value')->get();
        }
    }

    public function approve($transition)
    {
        if (!isset($transition['data']['transition'])) {
            Log::error('APPROVAL-MVR-PUBLIC-SERVICE-REG-APPROVE', ['Transition not set']);
            $this->customAlert('error', CustomMessage::ERROR);
            return;
        }

        $transition = $transition['data']['transition'];

        if ($this->checkTransition('public_service_registration_officer_review')) {
            $this->validate([
                'psPaymentCategoryId' => 'required|numeric|exists:public_service_payment_categories,id',
                'psPaymentMonthId' => [
                    'required',
                    'numeric',
                    'exists:public_service_payments_interval,id'
                ],
                'comments' => 'required|strip_tag',
            ], [
                'psPaymentMonthId.exists' => 'Payment month id does not exist'
            ]);

            try {
                DB::beginTransaction();

                if ($this->subject->payment) {
                    $this->subject->payment->public_service_payment_category_id = $this->psPaymentCategoryId;
                    $this->subject->payment->payment_months = PublicServicePaymentInterval::findOrFail($this->psPaymentMonthId, ['value'])->value;
                    $this->subject->payment->save();
                } else {
                    PublicServicePayment::create([
                        'public_service_motor_id' => $this->subject->id,
                        'public_service_payment_category_id' => $this->psPaymentCategoryId,
                        'payment_months' => PublicServicePaymentInterval::findOrFail($this->psPaymentMonthId, ['value'])->value,
                    ]);
                }

                DB::commit();

                $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
                $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
                return;
            } catch (\Exception $exception) {
                DB::rollBack();
                Log::error('APPROVAL-MVR-PUBLIC-SERVICE-REG-APPROVE', [$exception]);
                $this->customAlert('error', CustomMessage::ERROR);
                return;
            }

        }

        if ($this->checkTransition('public_service_registration_manager_review')) {
            $this->validate([
                'comments' => 'required|strip_tag',
            ]);
            try {

                $taxType = TaxType::query()->where('code', TaxType::PUBLIC_SERVICE)->firstOrFail();
                $business = Business::findOrFail($this->subject->business_id);

                DB::beginTransaction();
                $this->subject->status = PublicServiceMotorStatus::REGISTERED;
                $this->subject->approved_on = Carbon::now();
                $this->subject->save();
                $business->taxTypes()->syncWithoutDetaching([$taxType->id => ['currency' => Currencies::TZS]]);
                DB::commit();

                $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

                // Send approval email/sms
                if ($this->subject->status = PublicServiceMotorStatus::REGISTERED && $transition === 'public_service_registration_manager_review') {
                    event(new SendSms(SendCustomSMS::SERVICE, NULL, ['phone' => $this->subject->taxpayer->mobile, 'message' => "
                Hello {$this->subject->taxpayer->fullname}, your public service motor vehicle registration request for {$this->subject->mvr->plate_number} has been approved, You can now login to the system to make payments."]));
                }

            } catch (\Exception $exception) {
                DB::rollBack();
                Log::error('APPROVAL-MVR-PUBLIC-SERVICE-REG-APPROVE', [$exception]);
                $this->customAlert('error', CustomMessage::ERROR);
                return;

            }

            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());

        }
    }

    public function reject($transition)
    {
        if (!isset($transition['data']['transition'])) {
            Log::error('APPROVAL-MVR-PUBLIC-SERVICE-REG-REJECT', ['Transition not set']);
            $this->customAlert('error', CustomMessage::ERROR);
            return;
        }

        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|strip_tag',
        ]);

        try {
            DB::beginTransaction();

            if ($this->checkTransition('public_service_registration_officer_review')) {
                $this->subject->status = PublicServiceMotorStatus::CORRECTION;
                $this->subject->save();
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();

            if ($this->subject->status == PublicServiceMotorStatus::CORRECTION) {
                event(new SendSms(SendCustomSMS::SERVICE, NULL, ['phone' => $this->subject->taxpayer->mobile, 'message' => "
                Hello {$this->subject->taxpayer->fullname}, your public service registration request for {$this->subject->mvr->plate_number} requires correction, please login to the system to perform data update."]));
            }

            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('APPROVAL-MVR-PUBLIC-SERVICE-REG-REJECT', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }

    }


    protected $listeners = [
        'approve', 'reject'
    ];

    public function confirmPopUpModal($action, $transition)
    {
        $this->customAlert('warning', CustomMessage::ARE_YOU_SURE, [
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
        return view('livewire.approval.mvr.public-service-registration');
    }

}
