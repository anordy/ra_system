<?php

namespace App\Http\Livewire\LandLease;

use App\Enum\LeaseStatus;
use App\Models\DualControl;
use App\Models\LandLease;
use App\Models\LeaseCurrencyChangeApplication;
use App\Models\LeasePayment;
use App\Models\PartialPayment;
use App\Models\Taxpayer;
use App\Models\TaxType;
use App\Models\ZmBill;
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

class LeaseCurrencyApproveList extends DataTableComponent
{
    use CustomAlert, ExchangeRateTrait, PaymentsTrait;

    protected $model = LandLease::class;
    public const APPROVE = 'approve';
    public const REJECT = 'reject';

    protected $listeners = [
        'actionCurrencyChangeApplication',
    ];

    //create builder function

    public function builder(): builder
    {
        return LeaseCurrencyChangeApplication::with('landlease')->orderByDesc('lease_currency_change_applications.id');
    }


    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        $columns = [
            Column::make("S/N", 'id')
                ->format(function ($value, $row) {
                    return $row['rn'];
                })
                ->searchable()
                ->sortable(),
            Column::make("Tax Payer", 'taxpayer_id')
                ->format(function ($value, $row) {
                    return $this->getTaxPayer($row->taxpayer_id);
                })
                ->searchable()
                ->sortable(),
            Column::make("DP Number", 'landlease.dp_number')
                ->searchable()
                ->sortable(),
            Column::make("Commence Date", "landlease.commence_date")
                ->format(function ($value, $row) {
                    return date('d/m/Y', strtotime($value));
                })
                ->searchable()
                ->sortable(),
            Column::make("Request Currency", "to_currency")
                ->searchable()
                ->sortable(),
            Column::make("Reason", "reason")
                ->searchable()
                ->sortable(),
            Column::make("Approval Status", "status")
                ->view("land-lease.includes.approval-status"),
        ];

        // Check if the user has permission to see the Actions column
        if (Gate::allows('land-lease-approve-currency-change-application')) {
            $columns[] = Column::make("Actions", "landlease.id")
                ->view("land-lease.includes.currency-change-approval-actions");
        }

        return $columns;
    }

    public function reject($id)
    {
        if (!Gate::allows('land-lease-approve-currency-change-application')) {
            abort(403);
        }
        $this->customAlert('warning', 'Are you sure you want to reject this request?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Reject',
            'onConfirmed' => '',
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
        if (!Gate::allows('land-lease-approve-currency-change-application')) {
            abort(403);
        }
        $this->customAlert('warning', 'Are you sure you want to approve this request?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Approve',
            'onConfirmed' => 'actionCurrencyChangeApplication',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $id,
                'action' => 'approve'
            ],

        ]);
    }

    public function actionCurrencyChangeApplication($value)
    {
        if (!Gate::allows('land-lease-approve-currency-change-application')) {
            abort(403);
        }

        try {
            $data = (object)$value['data'];
            $leaseCurrencyChange = LeaseCurrencyChangeApplication::where('id', $data->id)->first();
            if (is_null($leaseCurrencyChange)) {
                abort(404);
            }
            DB::beginTransaction();
            switch ($data->action) {
                case self::APPROVE:
                    $leaseCurrencyChange->update(['status' => 'approved', 'approved_by' => Auth::user()->id]);
                    //cancel bill if control number was generated,
                    $bill = $this->getBill($leaseCurrencyChange->land_lease_id);

                    if ($bill) {
                        $this->cancelBill($bill, $leaseCurrencyChange->reason);
                    }

                    $leasePayment = $this->updateLeasePayment($leaseCurrencyChange->land_lease_id, $leaseCurrencyChange->to_currency);

                    if ($leasePayment) {
                        $leasePayment->landLease->payment_amount = $leasePayment->total_amount;
                        $leasePayment->landLease->save();
                        DB::commit();
                        $this->customAlert('success', 'Lease currency change has been approved successfully', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
                        return;
                    } else {
                        DB::rollBack();
                        $this->customAlert('error', 'Something went wrong', ['onConfirmed' => 'confirmed', 'timer' =>
                            2000]);
                        return;
                    }
                    break;
                case self::REJECT:
                    $comment = $value['value'] ?? null;
                    $leaseCurrencyChange->update(['status' => 'rejected', 'reject_reason' => $comment, 'approved_by' => Auth::user()->id]);
                    DB::commit();
                    $this->customAlert('success', 'Lease currency change has been rejected successfully', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
                    break;
                default:
                    $this->customAlert('error', 'Something went wrong, try again later', ['onConfirmed' => 'confirmed',
                        'timer' => 2000]);
            }

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error("LEASE-CURRENCY-CHANGE-APPLICATION-EXCEPTION: " . json_encode($exception));
            $this->customAlert('error', DualControl::ERROR_MESSAGE, ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }

    public function getTaxPayer($id)
    {
        return Taxpayer::where('id', $id)->first()->fullname;
    }

    public function updateLeasePayment($leaseId, $currency)
    {
        $leasePayment = LeasePayment::where('land_lease_id', $leaseId)->first();

        if ($leasePayment) {
            $leasePayment->total_amount = $this->convertToTsh($leasePayment->total_amount);
            $leasePayment->outstanding_amount = $this->convertToTsh($leasePayment->outstanding_amount);
            $leasePayment->total_amount_with_penalties = $this->convertToTsh($leasePayment->total_amount_with_penalties);
            $leasePayment->currency = $currency;
            $leasePayment->save();
        }
        $leasePayment->refresh();
        return $leasePayment;
    }

    public function getBill(int $leaseId): ?ZmBill
    {
        // Check for partial payments
        $partialPayment = PartialPayment::where('payment_type', $this->model)
            ->where('payment_id', $leaseId)
            ->first();

        if ($partialPayment) {
            return ZmBill::where('billable_id', $partialPayment->id)
                ->where('billable_type', get_class($partialPayment))
                ->first();
        }

        // Check for lease payments if no partial payment is found
        $leasePayment = LeasePayment::where('land_lease_id', $leaseId)->first();

        if ($leasePayment) {
            return ZmBill::where('billable_id', $leasePayment->id)
                ->where('billable_type', get_class($leasePayment))
                ->first();
        }

        // Return null if no bill is found
        return null;
    }

    private function generateControlNumber($leasePayment)
    {
        $landLease = $leasePayment->landlease;
        $taxTypes = TaxType::select('id', 'code', 'gfs_code')->where('code', 'land-lease')->first();
        $billitems = [
            [
                'billable_id' => $leasePayment->id,
                'billable_type' => get_class($leasePayment),
                'use_item_ref_on_pay' => 'N',
                'amount' => roundOff($leasePayment->total_amount, $this->getLeaseCurrency($landLease)),
                'currency' => $this->getLeaseCurrency($landLease),
                'gfs_code' => $taxTypes->gfs_code,
                'tax_type_id' => $taxTypes->id
            ],
        ];

        try {

            $taxpayer = $this->getTaxPayer($landLease->taxpayer_id);

            if ($landLease->category == 'business') {
                $payer_name = $landLease->businessLocation->business->name;
                $payer_type = get_class($landLease->businessLocation->business);
            } else {
                $payer_name = $taxpayer;
                $payer_type = get_class($landLease->taxpayer);
            }

            $payer_email = $landLease->taxpayer->email;
            $payer_phone = $landLease->taxpayer->mobile;
            $description = "Land Lease payment";
            $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
            $currency = $this->getLeaseCurrency($landLease);
            $createdby_type = get_class(Auth::user());
            $createdby_id = Auth::id();
            $exchange_rate = self::getExchangeRate($this->getLeaseCurrency($landLease));
            $payer_id = $landLease->taxpayer_id;
            $expire_date = Carbon::now()->addDays(30)->toDateTimeString(); // TODO: Recheck this date
            $billableId = $leasePayment->id;
            $billableType = get_class($leasePayment);

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
                $leasePayment->status = LeaseStatus::CN_GENERATED;
                $leasePayment->save();

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

    public function getLeaseCurrency($landLease)
    {
        return LeasePayment::select('currency')->where('land_lease_id', $landLease->id)->first()->currency;
    }

    public function convertToTsh($amount)
    {
        $rate = $this->getExchangeRate('USD');
        return $amount * $rate;
    }
}
