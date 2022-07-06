<?php

namespace App\Http\Livewire\Business\Closure;

use Exception;
use Carbon\Carbon;
use App\Models\BusinessTempClosure;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class RejectedClosuresTable extends DataTableComponent
{

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['is_extended']);
    }

    public function builder(): Builder
    {
        return BusinessTempClosure::query()->orderBy('business_temp_closures.opening_date', 'DESC');
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
            Column::make('Closing Date', 'closing_date')
                ->format(function($value, $row) { return Carbon::create($row->closing_date)->toFormattedDateString(); })
                ->sortable()
                ->searchable(),
            Column::make('Opening Date', 'opening_date')
                ->format(function($value, $row) { return Carbon::create($row->opening_date)->toFormattedDateString(); })
                ->sortable()
                ->searchable(),
            Column::make('Closure Reason', 'reason')
                ->sortable(),
            Column::make('Action', 'id')
                ->format(function ($value, $row) {
                        return <<< HTML
                        <button class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Approve" wire:click="changeStatus($value, 'approved')"><i class="fa fa-check"></i> </button>
                        <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Reject" wire:click="changeStatus($value, 'rejected')"><i class="fa fa-times"></i> </button>
                    HTML;
                })
                ->html(true),
        ];
    }

}
