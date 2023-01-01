<?php

namespace App\Http\Livewire\Reports\Debts\Previews;

use App\Traits\DebtReportTrait;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;


class AssessmentDebtReportPreviewTable extends DataTableComponent
{
    use LivewireAlert, DebtReportTrait;

    public $parameters;

    public function mount($parameters)
    {
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
    }

    public function columns(): array
    {
        return [
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

            Column::make("Currency", "currency")
                ->searchable()
                ->sortable(),

            Column::make("Actual Amount", "original_total_amount")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return number_format($value, 2);
                    }
                ),

                Column::make("Accumulated Amount", "total_amount")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        $accumulated = $row->total_amount - $row->original_total_amount;
                        return number_format($accumulated, 2);
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

            Column::make("Payment Due Date", "curr_payment_due_date")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        if (!$value) {
                            return '-';
                        }
                        return date('M, d Y', strtotime($value));
                    }
                ),
                Column::make("Status", "application_status")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return $value;
                    }
                ),
        ];
    }
}
