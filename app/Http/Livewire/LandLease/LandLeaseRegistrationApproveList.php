<?php

namespace App\Http\Livewire\LandLease;

use App\Models\LandLease;
use App\Models\User;
use App\Notifications\DatabaseNotification;
use App\Traits\CustomAlert;
use App\Traits\ExchangeRateTrait;
use App\Traits\PaymentsTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class LandLeaseRegistrationApproveList extends DataTableComponent
{
    use CustomAlert, ExchangeRateTrait, PaymentsTrait;

    protected $model = LandLease::class;
    protected $listeners = [
        'actionLeaseRequest',
    ];

    //create builder function

    public function builder(): builder
    {
        return LandLease::query();

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
            Column::make("S/N", 'is_registered')
                ->format(function ($value, $row) {
                    return $row['rn'];
                })
                ->searchable()
                ->sortable(),
            Column::make("Created Date", 'created_at')
                ->searchable()
                ->sortable(),
            Column::make("Completed Date", 'completed_at')
                ->searchable()
                ->sortable(),
//            Column::make("Initiator", 'created_by')
//                ->format(function ($value, $row) {
//                    return $this->getInitiator($row['created_by']);
//                })
//                ->searchable()
//                ->sortable(),
            Column::make("Approval Status", "approval_status")->view("land-lease.includes.registration-approval-status"),
            Column::make("Reject Reason", "comments")
                ->format(function ($value, $row) {
                    if ($row->comments) {
                        return $row->comments;
                    } else {
                        return 'No comments';
                    }
                })->searchable()
        ];

        if (Gate::allows('land-lease-approve-registration')) {
            $columns[] = Column::make("Actions", "id")->view("land-lease.includes.lease-registration-actions");
        }

        return $columns;
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

    public function getInitiator($id)
    {
        $initiator = User::findorFail($id);
        return $initiator ? $initiator->fname . ' ' . $initiator->lname : '-';
    }

}
