<?php

namespace App\Http\Livewire\DriversLicense;

use App\Models\DlApplicationStatus;
use App\Models\DlDriversLicense;
use App\Traits\CustomAlert;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class LicensesTable extends DataTableComponent
{
    use CustomAlert;

    public $licenseNumber;

    public function mount($licenseNumber = null)
    {
        $this->licenseNumber = $licenseNumber;
    }

    public function builder(): Builder
    {
        $query = DlDriversLicense::query()
            ->where('status', DlApplicationStatus::ACTIVE);

        if (!empty($this->licenseNumber)) {
            $query->where('license_number', $this->licenseNumber);
        }

        return $query->orderBy('id', 'desc');
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
                ->searchable(),
            Column::make("Type", "dl_license_application_id")
                ->format(function ($value, $row) {
                    return $row->application->type ?? 'N/A';
                }),
            Column::make("Name", "taxpayer_id")
                ->format(function ($value, $row) {
                    return $row->taxpayer->full_name ?? 'N/A';
                })
                ->searchable(),
            Column::make("Issue Date", "issued_date")
                ->format(fn($date) => Carbon::parse($date)->format('d-m-Y')),
            Column::make("Expire Date", "expiry_date")
                ->format(fn($date) => Carbon::parse($date)->format('d-m-Y')),
            Column::make("Request Date", "created_at")
                ->format(fn($date) => Carbon::parse($date)->format('d-m-Y')),
            Column::make("Status", "status")
                ->sortable(),
            Column::make('Action', 'id')
                ->format(function ($value) {
                    $url = route('drivers-license.licenses.show', encrypt($value));
                    return <<< HTML
                    <a class="btn btn-outline-primary btn-sm" href="$url"><i class="bi bi-eye-fill"></i>View</a>
                HTML;
                })
                ->html()
        ];
    }


}
