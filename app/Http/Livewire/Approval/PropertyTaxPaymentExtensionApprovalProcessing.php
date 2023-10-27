<?php

namespace App\Http\Livewire\Approval;

use App\Enum\BillStatus;
use App\Enum\PaymentExtensionStatus;
use App\Enum\PropertyPaymentCategoryStatus;
use App\Enum\PropertyStatus;
use App\Enum\PropertyTypeStatus;
use App\Events\SendMail;
use App\Events\SendSms;
use App\Jobs\PropertyTax\SendPropertyTaxApprovalMail;
use App\Jobs\PropertyTax\SendPropertyTaxApprovalSMS;
use App\Models\Currency;
use App\Models\FinancialYear;
use App\Models\PropertyTax\PaymentExtension;
use App\Models\PropertyTax\PropertyPayment;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PropertyTaxPaymentExtensionApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, CustomAlert, PaymentsTrait;

    public $modelId;
    public $modelName;
    public $comments;

    public $paymentExtension, $new_payment_due_date;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->paymentExtension = $modelName::findOrFail($this->modelId);

        $this->registerWorkflow($modelName, $this->modelId);
    }

    public function approve($transition)
    {
        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|string|strip_tag',
            'new_payment_due_date' => 'required|date'
        ]);

        if ($this->checkTransition('commissioner_approve')) {
            DB::beginTransaction();
            try {
                $this->new_payment_due_date = Carbon::create($this->new_payment_due_date);
                $this->paymentExtension->extension_to = $this->new_payment_due_date;
                $this->paymentExtension->status = PaymentExtensionStatus::APPROVED;
                $this->paymentExtension->save();

                $propertyPayment = PropertyPayment::findOrFail($this->paymentExtension->property_payment_id);
                $propertyPayment->curr_payment_date = $this->new_payment_due_date;
                $propertyPayment->save();
                $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
                DB::commit();

            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e);
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
                return;
            }
            try {
                $this->updateBill($propertyPayment->latestBill, $this->new_payment_due_date);
                $this->customAlert('success', 'Approved successfully');
                $this->flash(
                    'success',
                    __('Request submited successfully'),
                    [],
                    redirect()
                        ->back()
                        ->getTargetUrl(),
                );
            }catch (\Exception $e){
                Log::error($e);
            }
            $this->updateRequest(false);
        }
    }

    public function updateRequest($response){
        $extension = PaymentExtension::find($this->paymentExtension->id);
        $extension->bill_updated = $response;
        $extension->save();
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
                $this->property->save();

                $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
                DB::commit();
                event(new SendMail(SendToCorrectionReturnMail::SERVICE, $this->return));

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
        return view('livewire.approval.property_tax_payment_extension_approval');
    }
}
