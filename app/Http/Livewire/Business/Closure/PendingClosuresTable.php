<?php

namespace App\Http\Livewire\Business\Closure;

use App\Models\BusinessStatus;
use App\Traits\WithSearch;
use Carbon\Carbon;
use App\Models\BusinessTempClosure;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class PendingClosuresTable extends DataTableComponent
{
    use CustomAlert, WithSearch;


    protected $listeners = [
        'confirmed',
    ];

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
        return BusinessTempClosure::query()->where('business_temp_closures.status', BusinessStatus::PENDING)->orderBy('business_temp_closures.created_at', 'DESC');
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Closure Type', 'closure_type')
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
            Column::make('Closing Date', 'closing_date')
                ->format(function($value, $row) { return Carbon::create($row->closing_date)->toFormattedDateString(); })
                ->sortable()
                ->searchable(),
            Column::make('Opening Date', 'opening_date')
                ->format(function($value, $row) { return Carbon::create($row->opening_date)->toFormattedDateString(); })
                ->sortable()
                ->searchable(),
            Column::make('Is Extended', 'is_extended')
                ->format(function($value, $row) { 
                    if ($row->is_extended == false) {
                        return <<< HTML
                        <span class="badge badge-info py-1 px-2">No</span>
                    HTML;
                    } else {
                        return <<< HTML
                        <span class="badge badge-success py-1 px-2">Yes</span>
                    HTML;
                    }
                 })
                ->sortable()
                ->searchable()
                ->html(true), 
            Column::make('Status', 'status')->view('business.closure.includes.status'),
            Column::make('Action', 'id')
                ->view('business.closure.action'),
        ];
    }

}
