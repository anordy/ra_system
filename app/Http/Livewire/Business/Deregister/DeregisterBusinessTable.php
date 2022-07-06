<?php

namespace App\Http\Livewire\Business\Deregister;

use App\Models\BusinessDeregistration;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class DeregisterBusinessTable extends DataTableComponent
{


    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects([]);
    }

    public function builder(): Builder
    {
        return BusinessDeregistration::orderBy('business_deregistrations.created_at', 'DESC');
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('TIN', 'business.tin')
                ->sortable()
                ->searchable(),
            Column::make('Reg No.', 'business.reg_no')
                ->sortable()
                ->searchable(),
            Column::make('Date of De-registration', 'deregistration_date')
                ->format(function($value, $row) { return Carbon::create($row->deregistration_date)->toFormattedDateString(); })
                ->sortable()
                ->searchable(),
            Column::make('Reason', 'reason')
                ->sortable()
        ];
    }

}
