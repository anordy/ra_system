<?php

namespace App\Http\Livewire\Returns\Hotel;

use Carbon\Carbon;
use App\Models\TaxType;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Returns\HotelReturns\HotelReturn;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class TourOperatorReturnsTable extends DataTableComponent
{
    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class'   => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['financial_month_id']);
    }

    public function builder(): Builder
    {
        $tax = TaxType::where('code', TaxType::TOUR_OPERATOR)->first();

        return HotelReturn::where('tax_type_id', $tax->id)->doesntHave('debt')->orderBy('created_at', 'desc');
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
