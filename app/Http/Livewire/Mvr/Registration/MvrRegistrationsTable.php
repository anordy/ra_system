<?php

namespace App\Http\Livewire\Mvr\Registration;

use App\Models\MvrRegistration;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class MvrRegistrationsTable extends DataTableComponent
{

	public function builder(): Builder
	{
        return MvrRegistration::query();
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
                ->label(function ($row) {
                    return $row->plate_number ?? 'N/A';
                })
                ->searchable(),
            Column::make(__("Reg No"), "registration_number")
                ->label(function ($row) {
                    return $row->registration_number ?? 'N/A';
                })
                ->searchable(),
            Column::make(__("Reg Type"), "regtype.name")
                ->searchable(),
            Column::make(__("Plate No Color"), "platecolor.name")
                ->searchable(),
            Column::make(__("Plate No Size"), "platesize.name")
                ->searchable(),
            Column::make(__("Date"), "created_at")
                ->label(function ($row) {
                    return $row->created_at ?? 'N/A';
                }),
            Column::make(__('Status'), 'status')->view('mvr.registration.includes.status'),
            Column::make(__('Action'), 'id')->view('mvr.registration.includes.actions'),
        ];
    }



}
