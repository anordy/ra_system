<?php

namespace App\Http\Livewire\PublicService\DeRegistration;

use App\Models\PublicService\DeRegistration;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class DeRegistrationsTable extends DataTableComponent
{

    public $status;

    public function mount($status = "")
    {
        $this->status = $status;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function builder(): Builder
    {
        $query = DeRegistration::query()
            ->where('created_by', Auth::id());

        if ($this->status){
            $query->where('public_service_de_registrations.status', $this->status);
        }

        return $query->orderBy('public_service_de_registrations.created_at', 'DESC');
    }

    public function columns(): array
    {
        return [
            Column::make(__('Business Name'), 'motor.business.name')
                ->searchable(),
            Column::make('ZTN', 'motor.business.ztn_number')
                ->searchable(),
            Column::make(__('Plate Number'), 'motor.mvr.plate_number')
                ->sortable()
                ->searchable(),
            Column::make(__('De-registration Date'), 'de_registration_date')
                ->format(function ($value, $row) {
                    return Carbon::create($row->closing_date)->toFormattedDateString();
                })
                ->sortable()
                ->searchable(),
            Column::make(__('Request Status'), 'status')->view('public-service.de-registrations.includes.status'),
            Column::make(__('Action'), 'id')->view('public-service.de-registrations.includes.actions')
        ];

    }


}
