<?php

namespace App\Http\Livewire\Mvr\Deregistration;

use App\Models\MvrDeregistration;
use App\Traits\CustomAlert;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class DeregistrationsTable extends DataTableComponent
{
	use CustomAlert;

	public function builder(): Builder
	{
        return MvrDeregistration::where('mvr_deregistrations.taxpayer_id', Auth::id())->orderBy('mvr_deregistrations.created_at', 'desc');
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
            Column::make(__("Reason"), "reason.name")
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
            Column::make(__("De-Registration Date"), "deregistered_at")
                ->format(function ($value, $row) {
                    if ($value) {
                        return Carbon::create($value)->format('d M Y');
                    }
                    return 'N/A';
                }),
            Column::make(__('Status'), 'status')->view('mvr.de-registration.includes.status'),
            Column::make(__('Action'), 'id')->view('mvr.de-registration.includes.actions'),

        ];
    }



}
