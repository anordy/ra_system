<?php

namespace App\Http\Livewire\Reports\Debts\Previews;

use App\Models\Returns\TaxReturn;
use App\Models\TaxAssessments\TaxAssessment;
use App\Traits\DebtReportTrait;
use App\Traits\WithSearch;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;


class DebtWaiverReportPreviewTable extends DataTableComponent
{
    use CustomAlert, DebtReportTrait;

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
        $this->setAdditionalSelects(['debt_type', 'interest_amount', 'penalty_amount']);
    }

    public function columns(): array
    {
        return [
            Column::make('debt_id', 'debt_id')->hideIf(true),

            Column::make("Business", "id")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return $row->debt->business->name;
                    }
                ),

            Column::make("Business Location", "id")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return $row->debt->location->name;
                    }
                ),

            Column::make("Tax Type", "id")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return $row->debt->taxType->name;
                    }
                ),

            Column::make("Waiver Type", "category")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value) {
                        if ($value === 'interest') {
                            return 'Interest';
                        } else if ($value === 'penalty') {
                            return 'Penalty';
                        } else if ($value === 'both') {
                            return 'Penalty & Interest';
                        }
                    }
                ),

            Column::make("Currency", "id")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return $row->debt->currency;
                    }
                ),

            Column::make("Actual Amount", "id")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        if (get_class($row->debt) === TaxReturn::class) {
                            return number_format($row->debt->return->total_amount_due_with_penalties, 2);
                        } else if (get_class($row->debt) === TaxAssessment::class) {
                            return number_format($row->debt->original_total_amount, 2);
                        }
                    }
                ),

            Column::make("Waived Amount", "penalty_amount")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        $waived_amount = $row->penalty_amount + $row->interest_amount;
                        return number_format($waived_amount, 2);
                    }
                ),

            Column::make("Balance", "penalty_amount")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return number_format($row->debt->total_amount, 2);
                    }
                ),

        ];
    }
}
