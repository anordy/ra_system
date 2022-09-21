<?php

namespace App\Http\Livewire\Returns\Port;

use App\Models\Returns\Port\PortReturn;
use App\Models\TaxType;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AirportReturnTable extends DataTableComponent
{
    protected $listeners = ['filterData' => 'filterData', '$refresh'];
    public $data = [];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function filterData($data)
    {
        $this->data = $data;
        $this->emit('$refresh');
    }

    public function builder(): Builder
    {
        $data = $this->data;
        $tax = TaxType::where('code', TaxType::AIRPORT_SERVICE_SAFETY_FEE)->first();

        $filter = (new PortReturn)->newQuery();

        if ($data == []) {
            $filter->whereMonth('port_returns.created_at', '=', date('m'));
            $filter->whereYear('port_returns.created_at', '=', date('Y'));
        }
        if (isset($data['type']) && $data['type'] != 'all') {
            $filter->Where('return_category', $data['type']);
        }
        if (isset($data['month']) && $data['month'] != 'all' && $data['year'] != 'Custom Range') {
            $filter->whereMonth('port_returns.created_at', '=', $data['month']);
        }
        if (isset($data['year']) && $data['year'] != 'All' && $data['year'] != 'Custom Range') {
            $filter->whereYear('port_returns.created_at', '=', $data['year']);
        }
        if (isset($data['year']) && $data['year'] == 'Custom Range') {
            $filter->whereBetween('port_returns.created_at', [$data['from'], $data['to']]);
        }

        return $filter->where('tax_type_id', $tax->id)->orderBy('port_returns.created_at', 'desc');

    }

    public function columns(): array
    {
        return [
            Column::make('TIN', 'business.tin')
                ->sortable()
                ->searchable(),
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Branch Location', 'branch.name')
                ->sortable()
                ->searchable(),
            Column::make('Tax Type', 'taxtype.name')
                ->sortable()
                ->searchable(),

            Column::make('Infrastructure', 'infrastructure_tax')
                ->sortable(),
            Column::make('Infrastructure(ZNZ-TM)', 'infrastructure_znz_tm')
                ->sortable(),
            Column::make('Infrastructure(ZNZ-ZNZ)', 'infrastructure_znz_znz')
                ->sortable(),
            Column::make('Total VAT', 'total_amount_due_with_penalties')
                ->sortable()
                ->searchable(),
            Column::make('Payment Status', 'status')
                ->hideif(true),
            // Column::make('Status', 'id')->view('returns.port.includes.status'),
            Column::make('Action', 'id')
                ->view('returns.port.includes.actions'),
        ];
    }
}
