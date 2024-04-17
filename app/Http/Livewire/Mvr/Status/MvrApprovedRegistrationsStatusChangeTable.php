<?php

namespace App\Http\Livewire\Mvr\Status;

use App\Enum\MvrRegistrationStatus;
use App\Models\MvrRegistrationStatusChange;
use App\Models\MvrRegistrationTypeCategory;
use App\Models\MvrRequestStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class MvrApprovedRegistrationsStatusChangeTable extends DataTableComponent
{

	public function builder(): Builder
	{
        return MvrRegistrationStatusChange::query()->whereIn('mvr_registrations_status_change.status', [
            MvrRegistrationStatus::STATUS_REGISTERED,
            MvrRegistrationStatus::STATUS_PLATE_NUMBER_PRINTING,
            MvrRegistrationStatus::STATUS_PENDING_PAYMENT,
        ])->orderByDesc('mvr_registrations_status_change.created_at');
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
            Column::make(__("Chassis No"), "chassis.chassis_number")
                ->searchable(),
            Column::make(__("Plate No"), "plate_number")
                ->format(function ($value, $row) {
                    return $row->plate_number ?? 'PENDING';
                })
                ->searchable(),
            Column::make(__("Reg No"), "registration_number")
                ->format(function ($value, $row) {
                    return $row->registration_number ?? 'N/A';
                })
                ->searchable(),
            Column::make(__("Reg Type"), "regtype.name")
                ->searchable(),
            Column::make(__("Plate No Color"), "platecolor.name")
                ->searchable(),
            Column::make(__("Plate No Size"), "platesize.name")
                ->searchable(),
            Column::make(__("Registration Date"), "registered_at")
                ->format(function ($value, $row) {
                    if ($row->registered_at) {
                        return Carbon::create($row->registered_at)->format('d M Y');
                    }
                    return 'N/A';
                }),
            Column::make(__('Status'), 'status')->view('mvr.status.includes.status'),
//            Column::make(__('Action'), 'id')->view('mvr.status.includes.actions'),
            Column::make('Action', 'id')->format(function ($value, $row) {
                $value = "'".encrypt($value)."'";
                $url = route('mvr.transfer-ownership.show', $value);
                if (in_array($row->transfer_category->name, [MvrRegistrationTypeCategory::MILITARY,
                        MvrRegistrationTypeCategory::CORPORATE, MvrRegistrationTypeCategory::DIPLOMAT,
                        MvrRegistrationTypeCategory::GOVERNMENT
                    ]) && $row->request_status->name === MvrRequestStatus::STATUS_RC_ACCEPTED) {
                    return <<< HTML
                                        <button class="btn btn-outline-primary btn-sm" onclick="Livewire.emit('showModal', 'mvr.ownership-transfer-assign-plate-number', $value)">Assign Plate Number</button>
                                        <a class="btn btn-outline-primary btn-sm" href="$url">View</a>
                                    HTML;
                } else {
                    return <<< HTML
                    <a class="btn btn-outline-primary btn-sm" href="$url"><i class="fa fa-eye"></i>View</a>
                HTML;
                }

            })->html()
        ];
    }



}
