<?php

namespace App\Http\Livewire\Business;

use App\Models\Returns\TaxReturn;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class LocationReturnsTable extends DataTableComponent
{
    use CustomAlert;

    public $locationId;

    public function builder(): Builder
    {
        return TaxReturn::where('location_id', $this->locationId);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('Tax Type', 'taxtype.name')
                ->sortable()
                ->searchable(),
            Column::make('Principal', 'principal')
                ->format(function ($value, $row){
                    return number_format($value, 2);
                }),
            Column::make('Penalties', 'penalty')->format(function ($value, $row){
                return number_format($value, 2);
            }),
            Column::make('Interest', 'interest')->format(function ($value, $row){
                return number_format($value, 2);
            }),
            Column::make('Total', 'total_amount')
            ->format(function ($value, $row){
                return number_format($value, 2);
            }),
            Column::make('Outstanding Amount', 'outstanding_amount')
                ->format(function ($value, $row){
                    return number_format($value, 2);
                }),
            Column::make('Currency', 'currency'),
            Column::make('Financial Month', 'financial_month_id')
                ->format(function ($value, $row){
                    return "{$row->financialMonth->name} {$row->financialMonth->year->code}";
                })->sortable()->searchable(),
            Column::make('Return Category', 'return_category')
                ->format(function ($value){
                    return ucfirst($value);
                })->sortable()->searchable(),
            Column::make('Payment Status', 'payment_status')
                ->view('finance.includes.status')->sortable()->searchable(),
            Column::make('Filing Due Date', 'filing_due_date')
                ->format(function ($value){
                    return $value->toFormattedDateString();
                }),
            Column::make('Current Filing Due Date', 'curr_filing_due_date')
                ->format(function ($value){
                    return $value->toFormattedDateString();
                })->sortable()->searchable(),
        ];
    }

}
