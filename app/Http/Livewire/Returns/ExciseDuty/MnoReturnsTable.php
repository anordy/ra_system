<?php

namespace App\Http\Livewire\Returns\ExciseDuty;

use App\Models\Returns\ExciseDuty\MnoReturn;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class MnoReturnsTable extends DataTableComponent
{
    protected $listeners = ['filterData' => 'filterData'];
    public $data         = [];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class'   => 'table-bordered table-sm',
        ]);
    }

    public function filterData($data)
    {
        $this->data = $data;
        $this->builder();
    }

    public function builder(): Builder
    {
        $data   = $this->data;
        
        $filter = (new MnoReturn)->newQuery();
        if (isset($data['type']) && $data['type'] != 'all') {
            $filter->Where('return_category', $data['type']);
        }
        if (isset($data['month']) && $data['month'] != 'all') {
            $filter->whereMonth('mno_returns.created_at', '=', $data['month']);
        }
        if (isset($data['year']) && $data['year'] != 'All') {
            $filter->whereYear('mno_returns.created_at', '=', $data['year']);
        }

        return $filter->orderBy('mno_returns.created_at', 'desc');
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
