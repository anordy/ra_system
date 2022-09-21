<?php

namespace App\Http\Livewire\Returns\EmTransaction;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use App\Models\Returns\EmTransactionReturn;
use App\Traits\ReturnFilterTrait;
use Illuminate\Support\Facades\Gate;

class EmTransactionsTable extends DataTableComponent
{
    use  ReturnFilterTrait;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];
    public $data         = [];

    public function mount()
    {
        if (!Gate::allows('return-electronic-money-transaction-return-view')) {
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
        $this->emit('$refresh');
    }

    public function builder(): Builder
    {
        $filter      = (new EmTransactionReturn)->newQuery();
        $returnTable = EmTransactionReturn::getTableName();

        $filter = $this->dataFilter($filter, $this->data, $returnTable);

        return $filter->with('business', 'business.taxpayer', 'businessLocation')->orderBy('em_transaction_returns.created_at', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Branch / Location', 'businessLocation.name')
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
            Column::make('Date', 'created_at')
                ->sortable()
                ->format(function ($value, $row) {
                    return Carbon::create($value)->format('M d, Y');
                })
                ->searchable(),
            Column::make('Action', 'id')->view('returns.em-transaction.includes.actions'),
        ];
    }
}
