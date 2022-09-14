<?php

namespace App\Http\Livewire\Reports\Returns\Previews;

use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

use App\Traits\ReturnReportTrait;

class ReportPreviewTable extends DataTableComponent
{
    use LivewireAlert, ReturnReportTrait;

    public $parameters;

    public function mount($parameters)
    {
        // dd($parameters);
        $this->parameters = $parameters;
    }

    public function builder(): Builder
    {
        $mnos = $this->getRecords($this->parameters);
        return $mnos;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['tax_returns.business_id', 'tax_returns.location_id', 'tax_returns.financial_month_id', 'tax_returns.created_at', 'tax_returns.filed_by_id', 'tax_returns.filed_by_type']);
    }

    public function columns(): array
    {
        return [
            Column::make("Filing Date", "created_at")
                ->searchable()
                ->sortable()
                ->format(function ($value, $row) {
                    return date('d/m/Y', strtotime($value));
                }),
                
            Column::make("Business", "business_id")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return $row->business->name;
                    }
                ),

            Column::make("Business Location", "location_id")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return $row->location->name;
                    }
                ),
            
            Column::make("Tax Type", "tax_type_id")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return $row->taxType->name;
                    }
                ),

            Column::make("Reporting Month", "financial_month_id")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return $row->financialMonth->name;
                    }
                ),

            Column::make("Filed By", "id")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return $row->taxpayer->fullName;
                    }
                ),

            Column::make("Currency", "currency")
                ->searchable()
                ->sortable(),

            Column::make("Principal Amount", "principal")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return number_format($value, 2) ?? '-';
                    }
                ),

            Column::make("Interest", "interest")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return number_format($value, 2) ?? '-';
                    }
                ),

            Column::make("Penalty", "penalty")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return number_format($value, 2) ?? '-';
                    }
                ),

            //total_amount_due_with_penalties
            Column::make("Total Amount", "total_amount")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return number_format($value, 2);
                    }
                ),

            Column::make("Outstanding Amount", "outstanding_amount")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return number_format($value, 2);
                    }
                ),

            Column::make("Filing Due Date", "filing_due_date")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        if (!$value) {
                            return '-';
                        }
                        return date('d/m/Y', strtotime($value));
                    }
                )
        ];
    }
}
