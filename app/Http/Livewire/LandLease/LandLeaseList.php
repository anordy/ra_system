<?php

namespace App\Http\Livewire\LandLease;

use App\Enum\LeaseStatus;
use App\Models\BusinessLocation;
use App\Models\DualControl;
use App\Models\LandLease;
use App\Models\Taxpayer;
use App\Models\User;
use App\Notifications\DatabaseNotification;
use App\Traits\CustomAlert;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class LandLeaseList extends DataTableComponent
{
    use CustomAlert;

    protected $listeners = [
        'statusChange',
    ];

    //create builder function
    public function builder(): builder
    {
        return LandLease::whereNotNull('completed_at')->orderByDesc('created_at');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['land_leases.name', 'land_leases.phone', 'taxpayer_id']);
    }

    public function columns(): array
    {
        return [
            Column::make("S/N", 'created_at')
                ->format(function ($value, $row) {
                    return $row['rn'];
                })
                ->searchable()
                ->sortable(),
            Column::make("DP Number", "dp_number")
                ->searchable()
                ->sortable(),
            Column::make("Applicant Type", "category")
                ->format(function ($value) {
                    return ucwords($value);
                })
                ->searchable()
                ->sortable(),

            Column::make("Lease For", "lease_for")
                ->searchable()
                ->sortable(),
            Column::make("Area", "area")
                ->format(function ($value, $row) {
                    return number_format($value);
                })
                ->searchable()
                ->sortable(),
            Column::make("Region", "region.name")
                ->searchable()
                ->sortable(),
            Column::make("District", "district.name")
                ->searchable()
                ->sortable(),
            Column::make("Ward", "ward.name")
                ->searchable()
                ->sortable(),
            Column::make('Payment Amount (USD)', 'payment_amount')
                ->format(function ($value, $row) {
                    return number_format($value);
                })
                ->sortable(),
            Column::make("Lease Status", "lease_status")->view('land-lease.includes.lease-status'),
            Column::make("Applicant Status", "is_registered")->view("land-lease.includes.applicant-status"),
            Column::make("Actions", "id")->view("land-lease.includes.actions"),
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

    public function deactivate($id, $status)
    {
        if (!Gate::allows('land-lease-change-status')) {
            abort(403);
        }

        $this->customAlert('warning', 'Are you sure you want to de-register lease ?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => $status == 0 ? 'Activate' : 'Deregister',
            'onConfirmed' => 'statusChange',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $id,
            ],

        ]);
    }

    public function activate($id, $status)
    {
        if (!Gate::allows('land-lease-change-status')) {
            abort(403);
        }

        $this->customAlert('warning', 'Are you sure you want to re-register lease ?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => $status == 0 ? 'Activate' : 'Re-register',
            'onConfirmed' => 'statusChange',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $id,
            ],

        ]);
    }

    public function statusChange($value)
    {
        if (!Gate::allows('land-lease-change-status')) {
            abort(403);
        }
        try {
            $data = (object)$value['data'];
            $landLease = LandLease::select('id','lease_status','is_registered')->where('id', $data->id)
                ->first();

            if (is_null($landLease)) {
                $this->customAlert('error', 'Failed to change lease status');
                return;
            }

            //change lease status
            if ($landLease->lease_status === LeaseStatus::ACTIVE) {
                $landLease->lease_status = LeaseStatus::INACTIVE;
                $landLease->save();
            } elseif ($landLease->lease_status === LeaseStatus::INACTIVE) {
                $landLease->lease_status = LeaseStatus::ACTIVE;
                $landLease->save();
            }

            //send notification to lease officers
            $this->createNotification($landLease->dp_number);
            $this->customAlert('success', 'Lease status has been changed successfully', ['timer' => 4000]);
            return;
        } catch (\Exception $e) {

            report($e);
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
}
