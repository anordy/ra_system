<?php

namespace App\Http\Livewire\Mvr\Registration;

use App\Enum\MvrRegistrationStatus;
use App\Enum\ReportStatus;
use App\Models\MvrRegistration;
use App\Models\Region;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class MvrRegistrationsTable extends DataTableComponent
{

	public function builder(): Builder
	{
        return MvrRegistration::whereIn('mvr_registrations.status', [MvrRegistrationStatus::PENDING, MvrRegistrationStatus::CORRECTION, MvrRegistrationStatus::STATUS_PENDING_PAYMENT])
            ->orderByDesc('mvr_registrations.created_at');
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
//            Column::make(__("Plate Color"), "regtype.color.color")
//                ->searchable(),
            Column::make(__("Plate Size"), "platesize.name")
                ->searchable(),
            Column::make(__("Registration Date"), "registered_at")
                ->format(function ($value, $row) {
                    if ($row->registered_at) {
                        return Carbon::create($row->registered_at)->format('d M Y');
                    }
                    return 'N/A';
                }),
            Column::make(__('Status'), 'status')->view('mvr.registration.includes.status'),
            Column::make(__('Action'), 'id')->view('mvr.registration.includes.actions'),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Region')
                ->options([
                    'all' => ReportStatus::All,
                    'unguja' => ucfirst(Region::UNGUJA),
                    'pemba' => ucfirst(Region::PEMBA),
                ])
                ->filter(function (Builder $builder, string $value) {
                    if ($value != 'all') {
                        $builder->whereHas('region', function ($query) use ($value) {
                            $query->where('location', $value);
                        });
                    }
                }),
        ];
    }



}
