<?php

namespace App\Http\Livewire\Returns\BfoExciseDuty;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Returns\BFO\BfoReturn;
use App\Models\Returns\ReturnStatus;
use Illuminate\Support\Facades\Gate;

class BfoExciseDutyTable extends DataTableComponent
{
    protected $model     = BfoReturn::class;
    protected $listeners = ['filterData' => 'filterData', '$refresh'];

    public $data = [];

    public function mount()
    {
        if (!Gate::allows('return-bfo-excise-duty-return-view')) {
            abort(403);
        }
    }

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
        $filter = (new BfoReturn)->newQuery();
        
        if ($data == []) {
            $filter->whereMonth('bfo_returns.created_at', '=', date('m'));
            $filter->whereYear('bfo_returns.created_at', '=', date('Y'));
        }
        if (isset($data['type']) && $data['type'] != 'all') {
            $filter->Where('return_category', $data['type']);
        }
        if (isset($data['month']) && $data['month'] != 'all' && $data['year'] != 'Custom Range') {
            $filter->whereMonth('bfo_returns.created_at', '=', $data['month']);
        }
        if (isset($data['year']) && $data['year'] != 'All' && $data['year'] != 'Custom Range') {
            $filter->whereYear('bfo_returns.created_at', '=', $data['year']);
        }
        if (isset($data['year']) && $data['year'] == 'Custom Range') {
            $filter->whereBetween('bfo_returns.created_at', [$data['from'], $data['to']]);
        }
    
        return $filter->with('business', 'business.taxpayer', 'businessLocation')->orderBy('bfo_returns.created_at', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('TIN', 'business.tin')
                ->sortable()
                ->searchable(),
            Column::make('Branch Name', 'businessLocation.name')
                ->sortable()
                ->searchable(),
            Column::make('Total VAT', 'total_amount_due')
                ->sortable()
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                })
                ->searchable(),
            Column::make('Total VAT With Penalties', 'total_amount_due_with_penalties')
                ->sortable()
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                })
                ->searchable(),
            Column::make('Status', 'status')
                ->view('returns.excise-duty.bfo.includes.status')
                ->searchable()
                ->sortable(),
            Column::make('Date', 'created_at')
                ->sortable()
                ->format(function ($value, $row) {
                    return Carbon::create($value)->format('M d, Y');
                })
                ->searchable(),
            Column::make('Action', 'id')->view('returns.excise-duty.bfo.includes.actions'),
        ];
    }
}
