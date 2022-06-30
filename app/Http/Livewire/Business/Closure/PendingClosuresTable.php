<?php

namespace App\Http\Livewire\Business\Closure;

use id;
use Carbon\Carbon;
use App\Models\TemporaryBusinessClosure;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class PendingClosuresTable extends DataTableComponent
{


    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['is_extended', 'is_approved', 'approved_by']);
    }

    public function builder(): Builder
    {
        return TemporaryBusinessClosure::query()->where('is_approved', 0)->orderBy('temporary_business_closures.opening_date', 'DESC');
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
            Column::make('Reason', 'reason')
                ->sortable(),
            Column::make('Status', 'id')
                ->format(function ($value, $row) {
                    if ($row->is_approved == 0) {
                        return <<< HTML
                        <span class="badge badge-warning py-1 px-2">Pending</span>
                    HTML;
                    }
                })
                ->html(true),
            Column::make('Action', 'id')
                ->format(function ($value, $row) {
                    if ($row->is_approved == 0) {
                        return <<< HTML
                        <button class="btn btn-info btn-sm" wire:click="approve($value)">Approve</button>
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
