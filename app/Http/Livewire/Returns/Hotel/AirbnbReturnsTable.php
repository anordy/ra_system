<?php

namespace App\Http\Livewire\Returns\Hotel;

use Carbon\Carbon;
use App\Models\TaxType;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Traits\ReturnFilterTrait;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class AirbnbReturnsTable extends DataTableComponent
{
    use  ReturnFilterTrait;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];
    public $status;
    public $data = [];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class'   => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['financial_month_id', 'business_location_id']);
    }

    public function filterData($data)
    {
        $this->data = $data;
        $this->emit('$refresh');
    }

    public function builder(): Builder
    {
        $tax    = TaxType::where('code', TaxType::AIRBNB)->first();
        $filter = (new HotelReturn)->newQuery();

        $returnTable = HotelReturn::getTableName();

        $filter = $this->dataFilter($filter, $this->data, $returnTable);
    
        return $filter->where('tax_type_id', $tax->id)->orderBy('hotel_returns.created_at', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Branch / Location', 'businessLocation.name')
            ->sortable()
            ->searchable()
            ->format(function ($value, $row) {
                return "{$row->businessLocation->name}";
            }),
            Column::make('Tax Type', 'taxtype.name')
                ->sortable()
                ->searchable(),
            Column::make('Financial Month', 'financialMonth.name')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->financialMonth->name} {$row->financialMonth->year->code}";
                }),
            Column::make('Total VAT', 'total_amount_due')
                ->sortable()
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                }),
            Column::make('Total VAT with Penalty', 'total_amount_due_with_penalties')
                ->sortable()
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                }),
            Column::make('Infrastructure Tax', 'hotel_infrastructure_tax')
                ->sortable()
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                }),
            Column::make('Date', 'created_at')
                ->sortable()
                ->format(function ($value, $row) {
                    return Carbon::create($value)->format('M d, Y');
                })
                ->searchable(),
            Column::make('Action', 'id')->view('returns.hotel.includes.actions'),
        ];
    }
}
