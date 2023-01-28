<?php

namespace App\Http\Livewire\DriversLicense;

use App\Models\DlApplicationStatus;
use App\Models\DlLicenseApplication;
use App\Models\Taxpayer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class LicenseApplicationsTable extends DataTableComponent
{
	use LivewireAlert;

    public $status_id;

	public function builder(): Builder
	{
        if (empty($this->status_id)){
            return DlLicenseApplication::query();
        }else{
            return DlLicenseApplication::query()->whereIn('dl_application_status_id',[$this->status_id]);
        }
	}

    public function mount($status){
        $application_status = DlApplicationStatus::query()->where(['name'=>$status])->first();
        $this->status_id = $application_status->id ?? '';
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
            Column::make("Applicants Name", "taxpayer_id")
                ->format(fn($id)=>Taxpayer::query()->find($id)->fullname() ?? 'N/A')
                ->sortable(),
            Column::make("Applicants TIN", "taxpayer.tin")
                ->sortable(),
            Column::make("Type", "type")
                ->format(fn($type)=>ucwords(strtolower($type)))
                ->sortable(),
            Column::make("Application Date", "created_at")
                ->format(fn($date)=>Carbon::parse($date)->format('Y-m-d'))
                ->sortable(),
            Column::make("Status", "application_status.name")
                ->sortable(),
            Column::make('Action', 'id')
                ->format(function ($value) {
                    $url = route('drivers-license.applications.show',encrypt($value));
                    return <<< HTML
                    <a class="btn btn-outline-primary btn-sm" href="$url"><i class="fa fa-eye"></i>View</a>
                HTML;})
                ->html()
        ];
    }



}
