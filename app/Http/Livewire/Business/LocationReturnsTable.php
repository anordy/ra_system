<?php

namespace App\Http\Livewire\Business;

use App\Models\Returns\TaxReturn;
use App\Models\WorkflowTask;
use App\Traits\CustomAlert;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class LocationReturnsTable extends DataTableComponent
{
    use CustomAlert;

    public $locationId;

    public function builder(): Builder
    {
        return TaxReturn::where('location_id', $this->locationId)
            ->orderByDesc('tax_returns.financial_month_id');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['']);
    }

    public function columns(): array
    {
        return [
            Column::make('Tax Type', 'taxtype.name')
                ->sortable()
                ->searchable(),
            Column::make('Principal', 'principal')
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                }),
            Column::make('Penalties', 'penalty')->format(function ($value, $row) {
                return number_format($value, 2);
            }),
            Column::make('Interest', 'interest')->format(function ($value, $row) {
                return number_format($value, 2);
            }),
            Column::make('Total', 'total_amount')
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                }),
            Column::make('Outstanding Amount', 'outstanding_amount')
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                }),
            Column::make('Currency', 'currency'),
            Column::make('Financial Month', 'financial_month_id')
                ->format(function ($value, $row) {
                    return "{$row->financialMonth->name} {$row->financialMonth->year->code}";
                })->sortable()->searchable(),
            Column::make('Payment Status', 'payment_status')
                ->view('finance.includes.status')->sortable()->searchable(),
            Column::make('Filing Due Date', 'curr_filing_due_date')
                ->format(function ($value) {
                    return $value->toFormattedDateString();
                }),
            Column::make('Filed On', 'created_at')
                ->format(function ($value) {
                    return $value->toFormattedDateString();
                })->sortable()->searchable(),
        ];
    }

}
