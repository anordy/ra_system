<?php

namespace App\Http\Livewire\DriversLicense;

use App\Models\DlApplicationStatus;
use App\Models\DlLicenseApplication;
use App\Models\DlDriversLicenseOwner;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class LicenseApplicationsTable extends DataTableComponent
{
	use CustomAlert;

    public $status;

	public function builder(): Builder
	{
        if (empty($this->status)){
            return DlLicenseApplication::query();
        }else{
            return DlLicenseApplication::query()
                ->where('status', $this->status);
        }
	}

    public function mount($status){
        $this->status = $status ?? '';
    }

	public function configure(): void
    {
        $this->setPrimaryKey('id');

	    $this->setTableWrapperAttributes([
	      'default' => true,
	      'class' => 'table-bordered table-sm',
	    ]);

        $this->setAdditionalSelects(['last_name']);
    }

    public function columns(): array
    {
        return [
            Column::make(__("Applicant's Name"), "first_name")
                ->format(function ($value, $row) {
                    return $row->first_name . ' ' . $row->last_name;
                })
                ->sortable(),
            Column::make("Type", "type")
                ->format(fn($type)=>ucwords(strtolower($type)))
                ->sortable(),
            Column::make("Status", "status")
                ->sortable(),
            Column::make("Initiated Date", "created_at")
                ->format(fn($date) => Carbon::parse($date)->format('Y-m-d'))
                ->sortable(),
            Column::make('Action', 'id')
                ->format(function ($value) {
                    $url = route('drivers-license.applications.show',encrypt($value));
                    return <<< HTML
                    <a class="btn btn-primary btn-sm" href="$url"><i class="bi bi-eye-fill pr-1"></i>View Application</a>
                HTML;})
                ->html()
        ];
    }



}
