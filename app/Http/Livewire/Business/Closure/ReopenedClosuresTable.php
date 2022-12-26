<?php

namespace App\Http\Livewire\Business\Closure;

use Carbon\Carbon;
use App\Models\BusinessTempClosure;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class ReopenedClosuresTable extends DataTableComponent
{

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
        return BusinessTempClosure::query()->where('business_temp_closures.status', 'reopened')->orderBy('business_temp_closures.opening_date', 'DESC');
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
        Column::make('Re-opened On', 'reopening_date')
            ->format(function($value, $row) { 
                if ($row->reopening_date) {
                    return Carbon::create($row->reopening_date)->toFormattedDateString(); 
                }
                return 'N/A';
            })
            ->sortable()
            ->searchable(),
        Column::make('Re-opened Status', 'status')
            ->view('business.closure.includes.reopening'),
        Column::make('Action', 'id')
            ->view('business.closure.action'),
        ];
    }

}
