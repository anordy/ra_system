<?php

namespace App\Http\Livewire\Returns\LumpSum;

use App\Models\FinancialYear;
use App\Models\Returns\LumpSum\LumpSumReturn;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class LumpSumReturnsTable extends DataTableComponent
{
    use LivewireAlert;
    protected $listeners = ['filterData' => 'filterData'];

    public $data = [];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        // $this->setAdditionalSelects('lump_sum_returns.created_at as lp_created_at');
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
        $filter = (new LumpSumReturn)->newQuery();

        if (isset($data['type']) && $data['type'] != 'all') {
            $filter->Where('return_category', $data['type']);
        }
        if (isset($data['month']) && $data['month'] != 'all') {
            $filter->whereMonth('lump_sum_returns.created_at', '=', $data['month']);
        }
        if (isset($data['year']) && $data['year'] != 'All') {
            $filter->whereYear('lump_sum_returns.created_at', '=', $data['year']);
        }

        return $filter->orderBy('lump_sum_returns.created_at', 'desc');
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
            Column::make('Financial Year', 'financialyear.name')
                ->sortable()
                ->searchable(),
            Column::make('Quarter Of', 'quarter_name')
               ->sortable()
                ->searchable(),
            Column::make('Amount', 'total_amount_due_with_penalties')
                ->sortable()
                ->searchable(),
            Column::make('Control No', 'id')
            ->label(fn ($row) => $row->bill->control_number ?? $row->debt->bill->control_number ?? '')
            ->searchable(),
            Column::make('Status', 'status')
            ->view('returns.lump-sum.status')
                ->searchable()
                ->sortable(),
            Column::make('Action', 'id')
                ->view('returns.lump-sum.actions'),
        ];
    }
}
