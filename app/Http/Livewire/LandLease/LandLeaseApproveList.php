<?php

namespace App\Http\Livewire\LandLease;

use App\Enum\TransactionType;
use App\Models\DualControl;
use App\Models\LandLease;
use App\Models\LeasePayment;
use App\Models\PartialPayment;
use App\Models\TaxType;
use App\Traits\CustomAlert;
use App\Traits\ExchangeRateTrait;
use App\Traits\PaymentsTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
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

    public function getTaxPayer($landLease)
    {
        return $landLease->taxpayer;
    }

}
