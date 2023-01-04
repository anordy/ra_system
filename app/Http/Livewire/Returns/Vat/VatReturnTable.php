<?php

namespace App\Http\Livewire\Returns\Vat;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Returns\Vat\VatReturn;
use App\Traits\ReturnFilterTrait;

class VatReturnTable extends DataTableComponent
{
    use  ReturnFilterTrait;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];
    public $status;
    public $data     = [];
    protected $model = VatReturn::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['business_location_id']);
    }

    public function filterData($data)
    {
        $this->data = $data;
        $this->emit('$refresh');
    }

    public function builder(): Builder
    {
        $filter = (new VatReturn)->newQuery();

        $returnTable = VatReturn::getTableName();

        $filter = $this->dataFilter($filter, $this->data, $returnTable);
    
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
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->businessLocation->name}";
                }),
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
