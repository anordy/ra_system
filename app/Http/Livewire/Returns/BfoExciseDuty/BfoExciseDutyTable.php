<?php

namespace App\Http\Livewire\Returns\BfoExciseDuty;

use App\Traits\WithSearch;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Returns\BFO\BfoReturn;
use App\Models\Returns\ReturnStatus;
use App\Traits\ReturnFilterTrait;
use Illuminate\Support\Facades\Gate;

class BfoExciseDutyTable extends DataTableComponent
{
    use  ReturnFilterTrait;

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
        $this->setAdditionalSelects(['business_location_id']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class'   => 'table-bordered table-sm',
        ]);
    }

    public function filterData($data)
    {
        $this->data = $data;
        $this->emit('$refresh');
    }

    public function builder(): Builder
    {
        $filter      = (new BfoReturn)->newQuery();
        $returnTable = BfoReturn::getTableName();

        $filter = $this->dataFilter($filter, $this->data, $returnTable);
    
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
            Column::make('Branch / Location', 'businessLocation.name')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->businessLocation->name}";
                }),
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
            Column::make(__('Payment Status'), 'status')->format(function ($value, $row) {
                return view('returns.return-payment-status', ['row' => $row]);
            })
            ->searchable(),
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
