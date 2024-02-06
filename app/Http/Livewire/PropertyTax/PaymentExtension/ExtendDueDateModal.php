<?php

namespace App\Http\Livewire\PropertyTax\PaymentExtension;

use App\Enum\BillStatus;
use App\Enum\PaymentExtensionStatus;
use App\Jobs\Bill\UpdateBill;
use App\Models\PropertyTax\PaymentExtension;
use App\Models\PropertyTax\PropertyPayment;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Intervention\Image\Exception\NotFoundException;
use Livewire\Component;

class ExtendDueDateModal extends Component
{
    use CustomAlert;

    public $dueDate;

    public function mount() {

    }

    protected function rules()
    {
        return [
            'dueDate' => 'required|date|strip_tag',
        ];
    }

    public function submit()
    {

        $this->validate();

        try {

            $propertyPayments = PropertyPayment::where('payment_status', '!=', BillStatus::COMPLETE)
                ->where('curr_payment_date', '>=', Carbon::now()->toDateTimeString())
                ->where('curr_payment_date', '<=', Carbon::parse($this->dueDate)->toDateTimeString())
                ->get();

            foreach ($propertyPayments as $payment) {
                try {
                    DB::beginTransaction();

                    $currPaymentDate = $payment->curr_payment_date;
                    $payment->curr_payment_date = $this->dueDate;
                    $payment->save();

                    $payload = [
                        'property_payment_id' => $payment->id,
                        'requested_by_id' => Auth::user()->id,
                        'requested_by_type' => User::class,
                        'reasons' => 'Extended By CG',
                        'extension_from' => $currPaymentDate,
                        'status' => PaymentExtensionStatus::APPROVED
                    ];

                    PaymentExtension::create($payload);
                    DB::commit();
                    UpdateBill::dispatch($payment->latestBill, $this->dueDate);

                    // TODO: Send Message to Property Owner


                } catch (Exception $exception) {
                    DB::rollBack();
                    Log::error($exception);
                    throw $exception;
                }
            }

            $this->flash(
                'success',
                __('Request submitted successfully'),
                [],
                redirect()
                    ->back()
                    ->getTargetUrl(),
            );

        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong');
            return;
        }
    }

    public function render()
    {
        return view('livewire.property-tax.extension.extend-due-date');
    }
}
