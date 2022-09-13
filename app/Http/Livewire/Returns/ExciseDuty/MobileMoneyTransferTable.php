<?php

namespace App\Http\Livewire\Returns\ExciseDuty;

use Carbon\Carbon;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Returns\MmTransferReturn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;

class MobileMoneyTransferTable extends DataTableComponent
{
    protected $listeners = ['filterData' => 'filterData'];
    public $data         = [];

    public function mount()
    {
        if (!Gate::allows('return-mobile-money-transfer-view')) {
            abort(403);
        }
    }

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
        
        $filter = (new MmTransferReturn)->newQuery();
        if (isset($data['type']) && $data['type'] != 'all') {
            $filter->Where('return_category', $data['type']);
        }
        if (isset($data['month']) && $data['month'] != 'all') {
            $filter->whereMonth('mm_transfer_returns.created_at', '=', $data['month']);
        }
        if (isset($data['year']) && $data['year'] != 'All') {
            $filter->whereYear('mm_transfer_returns.created_at', '=', $data['year']);
        }

        return $filter->with('business', 'business.taxpayer', 'businessLocation')->orderBy('mm_transfer_returns.created_at', 'desc');
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
                ->view('returns.excise-duty.mobile-money-transfer.includes.status')
                ->searchable()
                ->sortable(),
            Column::make('Date', 'created_at')
                ->sortable()
                ->format(function ($value, $row) {
                    return Carbon::create($value)->format('M d, Y');
                })
                ->searchable(),
            Column::make('Action', 'id')->view('returns.excise-duty.mobile-money-transfer.includes.actions'),
        ];
    }
}
