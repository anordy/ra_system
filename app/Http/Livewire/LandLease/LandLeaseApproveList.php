<?php

namespace App\Http\Livewire\LandLease;

use App\Enum\LeaseStatus;
use App\Models\BusinessLocation;
use App\Models\DualControl;
use App\Models\LandLease;
use App\Models\LeasePayment;
use App\Models\PartialPayment;
use App\Models\Returns\ReturnStatus;
use App\Models\Taxpayer;
use App\Models\TaxType;
use App\Models\User;
use App\Notifications\DatabaseNotification;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use App\Traits\CustomAlert;
use App\Traits\ExchangeRateTrait;
use App\Traits\PaymentsTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class LandLeaseApproveList extends DataTableComponent
{
    use CustomAlert, ExchangeRateTrait, PaymentsTrait;

    protected $model = LandLease::class;
    protected $listeners = [
        'actionLeaseRequest',
    ];

    //create builder function

    public function builder(): builder
    {
        return PartialPayment::with('landlease')->where('payment_type', $this->model)->orderByDesc('partial_payments.id');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        //$this->setAdditionalSelects(['land_leases.name', 'land_leases.phone', 'taxpayer_id']);
    }

    public function columns(): array
    {
        return [
            Column::make("S/N", 'id')
                ->format(function ($value, $row) {
                    return $row['rn'];
                })
                ->searchable()
                ->sortable(),
            Column::make("DP Number", 'landlease.dp_number')
                ->searchable()
                ->sortable(),
            Column::make("Request Amount(USD)", 'amount')
                ->format(function ($value, $row) {
                    return number_format($value, 2, '.', ',');
                })
                ->searchable()
                ->sortable(),
            Column::make("Payment Amount(USD)", "landlease.payment_amount")
                ->format(function ($value, $row) {
                    return number_format($value, 2, '.', ',');
                })
                ->searchable()
                ->sortable(),
            Column::make("Reason", "comments")
                ->searchable()
                ->sortable(),
            Column::make("Approval Status", "status")->view("land-lease.includes.approval-status",),
            Column::make("Actions", "landlease.id")->view("land-lease.includes.partial-actions"),
        ];
    }


    public function getApplicantName($id)
    {
        $taxpayer = Taxpayer::find(decrypt($id));
        if (is_null($taxpayer)) {
            abort(404);
        }
        return $taxpayer->first_name . ' ' . $taxpayer->last_name;
    }

    public function getBusinessName($id)
    {
        $businessLocation = BusinessLocation::find(decrypt($id));
        if (is_null($businessLocation)) {
            abort(404);
        }
        return $businessLocation->business->name . ' | ' . $businessLocation->name;
    }

    public function reject($id)
    {
        $this->customAlert('warning', 'Are you sure you want to reject this payment request?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Reject',
            'onConfirmed' => 'actionLeaseRequest',
            'input' => 'text',
            'inputLabel' => 'Enter rejection reason (Optional)',
            'inputPlaceholder' => 'reason (optional)',
            'inputValidator' => '(value) => new Promise((resolve) => ' .
                '  resolve(' .
                '    /^[A-Za-z0-9 .,;!?\'"()\\-]+$/.test(value) ?' .
                '    undefined : "Invalid text"' .
                '  )' .
                ')',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $id,
                'action' => 'reject',
            ],
        ]);
    }

    public function approve($id)
    {
//        if (!Gate::allows('land-lease-change-status')) {
//            abort(403);
//        }

        $this->customAlert('warning', 'Are you sure you want to approve this payment request?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Approve',
            'onConfirmed' => 'actionLeaseRequest',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $id,
                'action' => 'approve'
            ],

        ]);
    }

    public function actionLeaseRequest($value)
    {
        try {
            $data = (object)$value['data'];
            //change status on partial payment
            $partialPayment = PartialPayment::where('id', $data->id)->latest()->first();
            if (is_null($partialPayment)) {
                abort(404);
            }
            DB::beginTransaction();
            switch ($data->action) {
                case 'approve':
                    $partialPayment->update(['status' => 'approved']);
                    $partialPayment->refresh();
					$this->generateLeasePartialPaymentControlNo($partialPayment);
                    //update lease payment
                    DB::commit();
                    $this->customAlert('success', 'Lease payment approved successfully', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
                    break;
                case 'reject':
                    $comment = $value['value'] ?? null;
                    $partialPayment->update(['status' => 'rejected', 'comments' => $comment]);
                    DB::commit();
                    $this->customAlert('success', 'Lease payment rejected successfully', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
                    break;
                default:
                    abort(404);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error("ACTION-LEASE-REQUEST: " . json_encode($exception->getMessage()));
            Log::error("ACTION-LEASE-REQUEST: " . json_encode($exception->getLine()));
            $this->customAlert('error', DualControl::ERROR_MESSAGE, ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }

    public function createNotification($dpNumber)
    {
        $leaseOfficers = User::whereHas('role', function ($query) {
            $query->where('name', 'Land Lease Officer');
        })->get();

        foreach ($leaseOfficers as $leaseOfficer) {
            $leaseOfficer->notify(new DatabaseNotification(
                $subject = 'Land Lease Edit Notification',
                $message = "Lease with DP No $dpNumber status been changed by " . auth()->user()->fname . " " . auth()
                        ->user()->lname,
                $href = 'land-lease.list',
            ));
        }
    }

    /**
     * @throws \DOMException
     * @throws RandomException
     */
    private function generateControlNumber($partialPayment)
    {
        $landLease = $partialPayment->landlease;

        $taxTypes = TaxType::select('id', 'code', 'gfs_code')->where('code', 'land-lease')->first();

        $billitems = [
            [
                'billable_id' => $partialPayment->id,
                'billable_type' => get_class($partialPayment),
                'use_item_ref_on_pay' => 'N',
                'amount' => roundOff($partialPayment->amount, $partialPayment->currency),
                'currency' => $partialPayment->currency,
                'gfs_code' => $taxTypes->gfs_code,
                'tax_type_id' => $taxTypes->id
            ],
        ];

        try {

            $taxpayer = $this->getTaxPayer($landLease)->first_name . ' ' . $this->getTaxPayer($landLease)->last_name;

            if ($landLease->category == 'business') {
                $payer_name = $landLease->businessLocation->business->name;
                $payer_type = get_class($landLease->businessLocation->business);
            } else {
                $payer_name = $taxpayer;
                $payer_type = get_class($this->getTaxPayer($landLease));
            }

            $payer_email = $this->getTaxPayer($landLease)->email;
            $payer_phone = $this->getTaxPayer($landLease)->mobile;
            $description = "Land Lease payment";
            $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
            $currency = $partialPayment->currency;
            $createdby_type = get_class(Auth::user());
            $createdby_id = Auth::id();
            $exchange_rate = self::getExchangeRate($partialPayment->currency);
            $payer_id = $this->getTaxPayer($landLease)->id;
            $expire_date = Carbon::now()->addDays(30)->toDateTimeString(); // TODO: Recheck this date
            $billableId = $partialPayment->id;
            $billableType = get_class($partialPayment);

            DB::beginTransaction();

            $zmBill = ZmCore::createBill(
                $billableId,
                $billableType,
                $taxTypes->id,
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
                $billitems
            );

            DB::commit();

            if (config('app.env') != 'local') {
                $this->generateGeneralControlNumber($zmBill);
                $control_number = null;
            } else {
                // We are local
                // Simulate successful control no generation
                $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $zmBill->zan_status = 'pending';
                $zmBill->control_number = random_int(2000070001000, 2000070009999);
                $zmBill->save();

                $control_number = $zmBill->control_number;
            }

            return $control_number;
        } catch
        (\Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function getTaxPayer($landLease)
    {
        return $landLease->taxpayer;
    }

}
