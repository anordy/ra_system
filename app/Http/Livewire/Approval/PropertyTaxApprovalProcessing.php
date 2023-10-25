<?php

namespace App\Http\Livewire\Approval;

use App\Enum\BillStatus;
use App\Enum\CondominiumStatus;
use App\Enum\PropertyPaymentCategoryStatus;
use App\Enum\PropertyStatus;
use App\Enum\PropertyTypeStatus;
use App\Events\SendMail;
use App\Events\SendSms;
use App\Jobs\PropertyTax\SendPropertyTaxApprovalMail;
use App\Jobs\PropertyTax\SendPropertyTaxApprovalSMS;
use App\Models\Currency;
use App\Models\FinancialYear;
use App\Models\PropertyTax\PropertyPayment;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PropertyTaxApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, CustomAlert, PaymentsTrait;

    public $modelId;
    public $modelName;
    public $comments;

    public $property;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->property = $modelName::findOrFail($this->modelId);

        $this->registerWorkflow($modelName, $this->modelId);
    }

    public function approve($transition)
    {
        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);

        if ($this->checkTransition('property_tax_officer_review')) {
            $this->validate(
                [
                    'comments' => ['nullable'],
                ],
            );

            DB::beginTransaction();
            try {
                $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

                // Generate URN Number


                // Update Status
                $this->property->status = PropertyStatus::APPROVED;
                $this->property->save();

                $amount = 0;

                // Calculate amount to be paid based on property type
                if ($this->property->type === PropertyTypeStatus::HOTEL) {
                    $amount = $this->property->star->amount_charged;
                } else if ($this->property->type === PropertyTypeStatus::CONDOMINIUM) {
                    if (!$this->property->unit) {
                        $this->customAlert('warning', 'Invalid condominium unit');
                        return;
                    }
                    // TODO: Fetch from System Settings
                    $amount = 10000;
                } else if ($this->property->type === PropertyTypeStatus::RESIDENTIAL_STOREY || $this->property->type === PropertyTypeStatus::STOREY_BUSINESS) {

                } else if ($this->property->type === PropertyTypeStatus::OTHER) {

                } else {
                    $this->customAlert('warning', 'Invalid property Type Provided');
                    return;
                }

                if (!$amount || $amount < 0) {
                    $this->customAlert('warning', 'Invalid payable amount');
                    return;
                }

                // Generate Bill
                $propertyPayment = PropertyPayment::create([
                    'property_id' => $this->property->id,
                    'financial_year_id' => FinancialYear::where('code', Carbon::now()->year)->firstOrFail()->id,
                    'currency_id' => Currency::where('iso', 'TZS')->firstOrFail()->id,
                    'amount' => $amount,
                    'interest' => 0,
                    'total_amount' => $amount,
                    'payment_date' => Carbon::now()->addMonths(3),
                    'curr_payment_date' => Carbon::now()->addMonths(3),
                    'payment_status' => BillStatus::SUBMITTED,
                    'payment_category' => PropertyPaymentCategoryStatus::NORMAL,
                ]);

                DB::commit();

                $this->generatePropertyTaxControlNumber($propertyPayment);

                // Send Notification
                event(new SendSms(SendPropertyTaxApprovalSMS::SERVICE, $this->property));
                event(new SendMail(SendPropertyTaxApprovalMail::SERVICE, $this->property));

                $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e);
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            }
        }
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);

        if ($this->checkTransition('application_filled_incorrect')) {

            DB::beginTransaction();
            try {

                $this->property->status = PropertyStatus::CORRECTION;

                if ($this->property->type === PropertyTypeStatus::CONDOMINIUM) {
                    $this->property->unit->status = CondominiumStatus::UNREGISTERED;
                    $this->property->unit->save();
                }

                $this->property->save();

                $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

                DB::commit();

                //event(new SendSms(SendToCorrectionReturnSMS::SERVICE, $this->return));
                //event(new SendMail(SendToCorrectionReturnMail::SERVICE, $this->return));

                $this->flash('success', 'Registration sent for correction', [], redirect()->back()->getTargetUrl());
            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e);
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            }
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
        return view('livewire.approval.property_tax_approval');
    }
}
