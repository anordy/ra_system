<?php

namespace App\Http\Livewire\Mvr\Reorder;

use App\Enum\MvrRegistrationStatus;
use App\Models\MvrRegistrationStatusChange;
use App\Models\MvrReorderPlateNumber;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class MvrReorderPlateNumberTable extends DataTableComponent
{

	public function builder(): Builder
	{
        return MvrReorderPlateNumber::query()->whereIn('mvr_reorder_plate_number.status', [
            MvrRegistrationStatus::PENDING,
            MvrRegistrationStatus::CORRECTION,
        ])->orderByDesc('mvr_reorder_plate_number.created_at');
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
            Column::make(__("Registration No"), "plate_number")
                ->format(function ($value, $row) {
                    return $row->plate_number ?? 'PENDING';
                })
                ->searchable(),
            Column::make(__("Serial No"), "registration_number")
                ->format(function ($value, $row) {
                    return $row->registration_number ?? 'N/A';
                })
                ->searchable(),
            Column::make(__("Reg Type"), "regtype.name")
                ->searchable(),
            Column::make(__("Registration No Size"), "platesize.name")
                ->searchable(),
            Column::make(__("Registration Date"), "registered_at")
                ->format(function ($value, $row) {
                    if ($row->registered_at) {
                        return Carbon::create($row->registered_at)->format('d M Y');
                    }
                    return 'N/A';
                }),
            Column::make(__('Status'), 'status')->view('mvr.reorder.includes.status'),
            Column::make(__('Action'), 'id')->view('mvr.reorder.includes.actions'),
        ];
    }
}
