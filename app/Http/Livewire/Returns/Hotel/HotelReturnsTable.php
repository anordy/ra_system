<?php

namespace App\Http\Livewire\Returns\Hotel;

use Carbon\Carbon;
use App\Models\Returns\HotelReturns\HotelReturn;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class HotelReturnsTable extends DataTableComponent
{
    public $status;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['financial_month_id']);
    }

    public function mount($status)
    {
        $this->status = $status;
    }



    public function builder(): Builder
    {
        if ($this->status == 'all') {
            return HotelReturn::query();
        } else if ($this->status == 'submitted') {
            return HotelReturn::where('hotel_returns.status', $this->status);
        }
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
            Column::make('Tax Type', 'taxtype.name')
                ->sortable()
                ->searchable(),
            Column::make('Total VAT', 'total_amount_due')
                ->sortable()
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                })
                ->searchable(),
            Column::make('Infrastructure Tax', 'hotel_infrastructure_tax')
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
            Column::make('Action', 'id')->view('returns.hotel.includes.actions'),

        ];
    }
}
