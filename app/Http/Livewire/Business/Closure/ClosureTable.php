<?php

namespace App\Http\Livewire\Business\Closure;

use id;
use Carbon\Carbon;
use App\Models\TemporaryBusinessClosure;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class ClosureTable extends DataTableComponent
{


    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['is_extended', 'is_approved', 'approved_by']);
    }

    public function builder(): Builder
    {
        return TemporaryBusinessClosure::query()->orderBy('temporary_business_closures.opening_date', 'DESC');
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
            Column::make('Approved By', 'approved_by')
                ->label(function($row) {
                    if ($row->is_approved == 1) {
                        return '<span>'.$row->user->fname. ' ' .$row->user->lname.'</span>';
                    } else {
                        return 'N/A';
                    }
                })
                ->sortable()
                ->searchable()
                ->html(true),
            Column::make('Reason', 'reason')
                ->sortable(),
            Column::make('Status', 'id')
                ->format(function ($value, $row) {
                    if ($row->is_approved == 0) {
                        return <<< HTML
                        <span class="badge badge-danger py-1 px-2">Not Approved</span>
                    HTML;
                    } else {
                        return <<< HTML
                        <span class="badge badge-success py-1 px-2">Approved</span>
                    HTML; 
                    }
                })
                ->html(true),
            Column::make('Action', 'id')
                ->format(function ($value, $row) {
                    if ($row->is_approved == 0) {
                        return <<< HTML
                        <button class="btn btn-outline-info btn-sm" wire:click="approve($value)">Approve</button>
                    HTML;
                    } else {
                        return <<< HTML
                        <button class="btn btn-outline-success btn-sm" disabled>Approved</button>
                    HTML; 
                    }
                })
                ->html(true),
        ];
    }

    public function approve($id)
    {
        return redirect()->to('/business/closure/' . $id . '/approve');
    }
}
