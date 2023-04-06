<?php

namespace App\Http\Livewire\DriversLicense;

use App\Models\DlApplicationStatus;
use App\Models\DlDriversLicense;
use App\Models\DlLicenseApplication;
use App\Models\Taxpayer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class LicensesTable extends DataTableComponent
{
	use LivewireAlert;


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
            Column::make("Driver's Name", "drivers_license_owner.taxpayer_id")
                ->format(fn($id)=>Taxpayer::query()->find($id)->fullname() ?? 'N/A')
                ->sortable()
                ->searchable(),
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
