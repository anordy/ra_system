<?php

namespace App\Http\Livewire\Vetting;

use App\Traits\WithSearch;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Returns\TaxReturn;
use App\Traits\ReturnFilterTrait;
use Illuminate\Support\Facades\Gate;

class VettingApprovalTable extends DataTableComponent
{
    use  ReturnFilterTrait, WithSearch;

    protected $model     = TaxReturn::class;
    protected $listeners = ['filterData' => 'filterData', '$refresh'];

    public $data = [];

    public function mount()
    {
        // if (!Gate::allows('return-bfo-excise-duty-return-view')) {
        //     abort(403);
        // }
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['location_id', 'tax_type_id', 'financial_month_id']);
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
        $filter      = (new TaxReturn())->newQuery();
        $returnTable = TaxReturn::getTableName();

        $filter = $this->dataFilter($filter, $this->data, $returnTable);
    
        return $filter->with('business', 'location', 'taxtype', 'financialMonth');
    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Branch / Location', 'location.name')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->location->name}";
                }),
            Column::make('Tax Type', 'taxtype.name')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->taxtype->name}";
                }),
            Column::make('Financial Month', 'financialMonth.name')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->financialMonth->name} {$row->financialMonth->year->code}";
                }),
            Column::make('Total', 'total_amount')
                ->sortable()
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                })
                ->searchable(),
            Column::make('Status', 'vetting_status')
                ->view('vetting.includes.status')
                ->searchable()
                ->sortable(),
            Column::make('Date', 'created_at')
                ->sortable()
                ->format(function ($value, $row) {
                    return Carbon::create($value)->format('M d, Y');
                })
                ->searchable(),
            Column::make('Action', 'id')->view('vetting.includes.actions'),
        ];
    }
}
