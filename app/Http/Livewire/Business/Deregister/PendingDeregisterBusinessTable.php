<?php

namespace App\Http\Livewire\Business\Deregister;

use App\Models\BusinessDeregistration;
use App\Models\BusinessStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class PendingDeregisterBusinessTable extends DataTableComponent
{


    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['business_deregistrations.status']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function builder(): Builder
    {
        return BusinessDeregistration::where('business_deregistrations.status', BusinessStatus::PENDING)->orderBy('business_deregistrations.created_at', 'DESC');
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Deregistration Type', 'deregistration_type')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    if ($value == 'all') {
                        return 'All Locations';
                    } else {
                        return 'Single Location';
                    }
                }),
            Column::make('Location', 'location.name')
                ->sortable()
                ->searchable(),
            Column::make('Date of De-registration', 'deregistration_date')
                ->format(function($value, $row) { return Carbon::create($row->deregistration_date)->toFormattedDateString(); })
                ->sortable()
                ->searchable(),
            Column::make('Action', 'id')
                ->view('business.deregister.action'),

        ];
    }

}
