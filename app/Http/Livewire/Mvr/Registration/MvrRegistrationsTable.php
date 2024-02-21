<?php

namespace App\Http\Livewire\Mvr\Registration;

use App\Enum\MvrRegistrationStatus;
use App\Models\MvrRegistration;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class MvrRegistrationsTable extends DataTableComponent
{

	public function builder(): Builder
	{
        return MvrRegistration::query()->whereIn('mvr_registrations.status', [
            MvrRegistrationStatus::PENDING,
            MvrRegistrationStatus::CORRECTION,
        ])->orderByDesc('mvr_registrations.created_at');
    }

    public function mount(){
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
                    return Carbon::create($row->registered_at)->format('d M Y')?? 'N/A';
                }),
            Column::make(__('Status'), 'status')->view('mvr.registration.includes.status'),
            Column::make(__('Action'), 'id')->view('mvr.registration.includes.actions'),
        ];
    }



}
