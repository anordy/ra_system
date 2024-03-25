<?php

namespace App\Http\Livewire\RoadInspectionOffence;

use App\Models\RioRegister;
use App\Models\Taxpayer;
use App\Traits\WithSearch;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RegisterTable extends DataTableComponent
{
	use CustomAlert;

    public function builder(): Builder
	{
        return RioRegister::query();
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
            Column::make("Driver Name", "drivers_license_owner.taxpayer_id")
                ->format(fn($taxpayer_id)=>Taxpayer::query()->find($taxpayer_id)->fullname() ?? 'N/A')
                ->sortable(),
            Column::make("Plate Number", "motor_vehicle_registration.plate_number")
                ->sortable(),
            Column::make("Date", "date")->format(fn($date)=>Carbon::parse($date)->format('Y-m-d'))
                ->sortable(),
            Column::make("Offences Count", "id")->format(fn($id)=>RioRegister::query()->find($id)->register_offences()->count())
                ->sortable(),
            Column::make("Restriction On", "block_type")
                ->sortable(),
            Column::make("Restriction Status", "block_status")
                ->sortable(),
            Column::make('Action', 'id')
                ->format(function ($value) {
                    $url = route('rio.register.show',encrypt($value));
                    return <<< HTML
                    <a class="btn btn-outline-primary btn-sm" href="$url"><i class="bi bi-eye-fill"></i>View</a>
                HTML;
                })
                ->html()
        ];
    }



}
