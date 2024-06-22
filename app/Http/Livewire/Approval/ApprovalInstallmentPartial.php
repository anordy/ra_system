<?php

namespace App\Http\Livewire\Approval;

use App\Enum\BillStatus;
use App\Enum\InstallmentRequestStatus;
use App\Enum\LeaseStatus;
use App\Enum\PaymentStatus;
use App\Http\Livewire\Installment\InstallmentPayment;
use App\Models\Installment\InstallmentExtensionRequest;
use App\Models\PartialPayment;
use App\Models\TaxType;
use App\Services\Api\ZanMalipoInternalService;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ApprovalInstallmentPartial extends Component
{
    use WorkflowProcesssingTrait, CustomAlert,PaymentsTrait;

    public $modelId;
    public $modelName;
    public $comments;
    public $requestedAmount;
    public $partialId;
    public $taxTypes;

    public $task;


    protected $listeners = [
        'approve', 'reject','rejected'
    ];

    public function mount($modelName, $modelId, $requestedAmount, $partialId)
    {
        $this->modelName = $modelName;
        $this->modelId   = decrypt($modelId);
        $this->partialId   = decrypt($partialId);
        $this->requestedAmount = $requestedAmount;
        $this->taxTypes = TaxType::all();
        $getBusines = $this->modelName::with('installments')->findOrFail($this->modelId);
//dd($getBusines->installments, $this->modelName);
//        $this->registerWorkflow($modelName, $this->modelId);
//        $this->task = $this->subject;
    }

    public function approve($transition)
    {
//        dd($transition,$this->subject);
//        $transition = $transition['data']['transition'];
        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);


        try {
            DB::beginTransaction();

            $installmentList = $this->modelName::findOrFail($this->modelId);
            $installmentList->status = BillStatus::PAID_PARTIALLY;

//            dd($this->modelName, $installmentList);
            $partialPayment = PartialPayment::findOrFail($this->partialId);
            $partialPayment->status = InstallmentRequestStatus::APPROVED;
            $partialPayment->comments = $this->comments;
//            dd($partialPayment, $this->requestedAmount, $this->partialId,$this->modelId,$partialPayment->installmentItem->installment->installable->business->name);

            $billItems = [
                [
                    'Name' => $partialPayment->installmentItem->installment->installable->business->name,
                    'gfs_code' => TaxType::where('code', TaxType::INVESTIGATION)->firstOrFail()->gfs_code,
                    'amount' =>  $this->requestedAmount,
                    'currency' => $partialPayment->installmentItem->installment->installable->currency,
                    'tax_type_id'=> $partialPayment->installmentItem->installment->installable->taxType->id
                ]
            ];

//            dd($billItems,$partialPayment);

            // Generate control number logic here
            $controlNumber = $this->generateControlNumber($partialPayment,$billItems);
//            $partialPayment->control_number = $controlNumber;
//            dd($partialPayment);
            $partialPayment->save();
//dd($partialPayment,$partialPayment->control_number);

            $partialPayment = $this->modelName::findOrFail($this->modelId);
            $partialPayment->status = InstallmentRequestStatus::APPROVED;
            $partialPayment->comments = $this->comments;


//                $list = DB::table('installment_lists')->where('id',$listId)->update([
//                    'due_date'=>$newDate
//                ]);

//                if($list >= 1){
//                    $this->subject->save();
//                }




                // Dispatch notification via email and mobile phone
//                event(new SendSms(SendInstallmentApprovedSMS::SERVICE, $installment));
//                event(new SendMail(SendInstallmentApprovedMail::SERVICE, $installment));

            DB::commit();

            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact support for assistance.');
            return;
        }

    }

    public function reject($transition)
    {
//        $transition = $transition['data']['transition'];
        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);


        try {
            DB::beginTransaction();

//            if ($this->checkTransition('rejected')) {

                $this->subject->status = InstallmentRequestStatus::REJECTED;

                $installable = InstallmentExtensionRequest::findOrFail($this->subject->id);

                // Update tax return details
                $installable->update([
                    'status' => InstallmentRequestStatus::REJECTED
                ]);

                $this->subject->save();

                // Dispatch notification via email and mobile phone
//                event(new SendSms(SendInstallmentApprovedSMS::SERVICE, $installment));
//                event(new SendMail(SendInstallmentApprovedMail::SERVICE, $installment));
//            }

//            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            DB::commit();

            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact support for assistance.');
            return;
        }

    }

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

    public function generateControlNumber($partialPayment, $billItems)
    {

        $taxpayer = $partialPayment->installmentItem->installment->installable->business;
        $tax_type = $partialPayment->installmentItem->installment->installable->taxType->id;
        $exchange_rate = $this->getExchangeRate($partialPayment->installmentItem->installment->installable->currency);

//dd($taxpayer,$tax_type,$partialPayment->installmentItem->installment->installable->currency,get_class($partialPayment));
        $payer_type = get_class($partialPayment);
        $payer_name = $taxpayer->name;
        $payer_email = $taxpayer->email;
        $payer_phone = $taxpayer->mobile;
        $description = "Payment for Partial Installment for ".$taxpayer->name;
        $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
        $currency = $partialPayment->installmentItem->installment->installable->currency;
        $createdby_type = get_class(Auth::user());
        $createdby_id = Auth::id();
        $payer_id = $partialPayment->id;
        $expire_date = Carbon::now()->addMonth()->toDateTimeString();
        $billableId = $partialPayment->id;
        $billableType = get_class($partialPayment);

        $bill = ZmCore::createBill(
            $billableId,
            $billableType,
            $tax_type,
            $payer_id,
            $payer_type,
            $payer_name,
            $payer_email,
            $payer_phone,
            $expire_date,
            $description,
            $payment_option,
            $currency,
            $exchange_rate,
            $createdby_id,
            $createdby_type,
            $billItems
        );


        if (config('app.env') != 'local') {
            $createBill = (new ZanMalipoInternalService)->createBill($bill);
        } else {
            // We are local
            $partialPayment->status = InstallmentRequestStatus::APPROVED;


            // Simulate successful control no generation
            $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $bill->zan_status = 'pending';
            $bill->control_number = random_int(2000070001000, 2000070009999);
            $bill->save();

            $partialPayment->control_number = $bill->control_number;
            $partialPayment->save();
//            dd($partialPayment);
            // $this->flash('success', 'Your landLease was submitted, you will receive your payment information shortly - test');
        }
    }
    
    public function render()
    {
        return view('livewire.approval.approval-installment-partial');
    }
}
