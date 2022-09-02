<?php

namespace App\Http\Livewire\Returns\ExciseDuty;

use App\Models\Returns\ExciseDuty\MnoReturn;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class MnoReturnsTable extends DataTableComponent
{
    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class'   => 'table-bordered table-sm',
        ]);
    }

    public function builder(): Builder
    {
        return MnoReturn::query()
        ->doesntHave('debt')
        ->orderBy('mno_returns.created_at', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('Taxpayer Name', 'business_id')
            ->sortable()
            ->searchable()
            ->format(function ($value, $row) {
                return "{$row->business->taxpayer->fullName}";
            }),
                
            Column::make('Business Name', 'business_id')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->business->name}";
                }),
            Column::make('Branch / Location', 'businessLocation.name')
            ->sortable()
            ->searchable(),

            Column::make('Total Payable Vat', 'total_amount_due_with_penalties')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return number_format($row->total_amount_due_with_penalties, 2);
                }),
                
            Column::make('Status', 'status')
                ->view('returns.excise-duty.mno.includes.status'),
            
            Column::make('Action', 'id')
                ->view('returns.excise-duty.mno.includes.actions'),
        ];
    }
}
