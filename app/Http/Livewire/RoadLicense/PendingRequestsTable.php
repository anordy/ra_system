<?php

namespace App\Http\Livewire\RoadLicense;

use App\Enum\RoadLicenseStatus;
use App\Models\RoadLicense\RoadLicense;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PendingRequestsTable extends DataTableComponent
{

	public function builder(): Builder
	{
        return RoadLicense::whereIn('road_licenses.status', [RoadLicenseStatus::PENDING, RoadLicenseStatus::CORRECTION])
            ->orderBy('road_licenses.created_at', 'desc');
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
            Column::make(__("Chassis No"), "registration.chassis.chassis_number")
                ->searchable(),
            Column::make(__("Plate No"), "registration.plate_number")
                ->format(function ($value, $row) {
                    return $value ?? 'PENDING';
                })
                ->searchable(),
            Column::make(__("Reg No"), "registration.registration_number")
                ->format(function ($value, $row) {
                    return $value ?? 'N/A';
                })
                ->searchable(),
            Column::make(__("Registration Type"), "registration.regtype.name")
                ->searchable(),
            Column::make(__("Issued Date"), "issued_date")
                ->format(function ($value, $row) {
                    if ($value) {
                        return Carbon::create($value)->format('d M Y');
                    }
                    return 'N/A';
                }),
            Column::make(__("Expiry Date"), "expire_date")
                ->format(function ($value, $row) {
                    if ($value) {
                        return Carbon::create($value)->format('d M Y');
                    }
                    return 'N/A';
                }),
            Column::make(__('Status'), 'status')->view('road-license.includes.status'),
            Column::make(__('Action'), 'id')->view('road-license.includes.actions'),
        ];
    }



}
