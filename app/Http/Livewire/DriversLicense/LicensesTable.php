<?php

namespace App\Http\Livewire\DriversLicense;

use App\Models\DlDriversLicense;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class LicensesTable extends DataTableComponent
{
	use CustomAlert;


	public function builder(): Builder
	{
        return DlDriversLicense::query();
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
            Column::make("License Number", "license_number")
                ->sortable()
                ->searchable(),
            Column::make("TIN", "drivers_license_owner.taxpayer.tin")
                ->sortable(),
            Column::make("Issue Date", "issued_date")
                ->format(fn($date)=>Carbon::parse($date)->format('Y-m-d'))
                ->sortable(),
            Column::make("Expire Date", "expiry_date")
                ->format(fn($date)=>Carbon::parse($date)->format('Y-m-d'))
                ->sortable(),
            Column::make('Action', 'id')
                ->format(function ($value) {
                    $url = route('drivers-license.licenses.show',encrypt($value));
                    return <<< HTML
                    <a class="btn btn-outline-primary btn-sm" href="$url"><i class="fa fa-eye"></i>View</a>
                HTML;})
                ->html()
        ];
    }



}
