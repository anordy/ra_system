<?php

namespace App\Http\Livewire\Returns\Vat;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Returns\Vat\VatReturn;

class VatReturnTable extends DataTableComponent
{
    protected $model     = VatReturn::class;
    protected $listeners = ['filterData' => 'filterData', '$refresh'];
    public $status;
    public $data = [];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function filterData($data)
    {
        $this->data = $data;
        $this->emit('$refresh');
    }

    public function builder(): Builder
    {
        $data   = $this->data;
        $filter = (new VatReturn)->newQuery();

        if ($data == []) {
            $filter->whereMonth('vat_returns.created_at', '=', date('m'));
            $filter->whereYear('vat_returns.created_at', '=', date('Y'));
        }
        
        if (isset($data['type']) && $data['type'] != 'all') {
            $filter->Where('return_category', $data['type']);
        }
        if (isset($data['month']) && $data['month'] != 'all' && $data['year'] != 'Custom Range') {
            $filter->whereMonth('vat_returns.created_at', '=', $data['month']);
        }
        if (isset($data['year']) && $data['year'] != 'All' && $data['year'] != 'Custom Range') {
            $filter->whereYear('vat_returns.created_at', '=', $data['year']);
        }
        if (isset($data['year']) && $data['year'] == 'Custom Range') {
            $filter->whereBetween('vat_returns.created_at', [$data['from'], $data['to']]);
        }
    
        return $filter->select('editing_count', 'taxpayers.last_name', 'taxpayers.first_name')->with('business', 'business.taxpayer')->orderBy('vat_returns.created_at', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('Taxpayer Name', 'business.taxpayer.first_name')
                ->format(function ($value, $row) {
                    return "{$row->first_name} {$row->last_name}";
                })
                ->sortable()->searchable(),
            Column::make('Business Name', 'business.name')
                ->sortable()->searchable(),
            Column::make('Branch / Location', 'businessLocation.name')
            ->sortable()
            ->searchable(),
            Column::make('Total Input Tax', 'total_input_tax')
                ->format(function ($value, $row) {
                    return number_format($row->total_input_tax, 2);
                })
                ->sortable()->searchable(),
            Column::make('Total Payable Vat', 'total_vat_payable')
                ->format(function ($value, $row) {
                    return number_format($row->total_vat_payable, 2);
                })
                ->sortable()->searchable(),
            Column::make('Total Amount Due', 'total_amount_due')
                ->format(function ($value, $row) {
                    return number_format($row->total_amount_due, 2);
                })
                ->sortable()->searchable(),
            Column::make('Grant Total Vat', 'total_amount_due_with_penalties')
                ->format(function ($value, $row) {
                    return number_format($row->total_amount_due_with_penalties, 2);
                })
                ->sortable()->searchable(),
            Column::make('Status', 'status')
                ->view('returns.vat_returns.includes.approvedStatus'),
            Column::make('Action', 'id')
                ->view('returns.vat_returns.includes.actions'),
        ];
    }
}
