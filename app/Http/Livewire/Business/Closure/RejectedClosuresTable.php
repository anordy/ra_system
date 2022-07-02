<?php

namespace App\Http\Livewire\Business\Closure;

use id;
use Carbon\Carbon;
use App\Models\TemporaryBusinessClosure;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class RejectedClosuresTable extends DataTableComponent
{


    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['is_extended', 'status', 'rejected_by']);
    }

    public function builder(): Builder
    {
        return TemporaryBusinessClosure::query()->where('status', 'rejected')->orderBy('temporary_business_closures.opening_date', 'DESC');
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
            Column::make('Rejected By', 'rejected_by')
                ->label(function($row) {
                        return '<span>'.$row->rejected->fname. ' ' .$row->rejected->lname.'</span>';
                })
                ->sortable()
                ->searchable()
                ->html(true),
            Column::make('Reason', 'reason')
                ->sortable(),
            Column::make('Status', 'status')
                ->format(function ($value, $row) {
                        return <<< HTML
                        <span class="badge badge-danger py-1 px-2">Rejected</span>
                    HTML;
                })
                ->html(true),
        ];
    }

}
