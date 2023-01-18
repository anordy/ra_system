<?php

namespace App\Http\Livewire\Reports\Dispute\Previews;

use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxType;
use App\Traits\AssessmentReportTrait;
use App\Traits\DisputeReportTrait;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class DisputePreviewTable extends DataTableComponent
{
    use LivewireAlert, DisputeReportTrait;

    public $parameters;

    public function mount($parameters)
    {
        $this->parameters = $parameters;
    }

    public function builder(): Builder
    {
        $assessments = $this->getRecords($this->parameters);
        return $assessments;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['business_id', 'location_id', 'disputes.tax_in_dispute', 'disputes.tax_not_in_dispute', 'disputes.tax_deposit']);
    }

    public function columns(): array
    {
        return [
            Column::make("Register Date", "created_at")
                ->format(function ($value, $row) {
                    return date('d/m/Y', strtotime($value));
                })
                ->searchable()
                ->sortable(),
            Column::make("Business Name")
                ->label(
                    function ($row) {
                        return $row->business->name;
                    }
                )
                ->searchable()
                ->sortable(),
            Column::make("Business Location")
                ->label(
                    function ($row) {
                        return $row->location->name ?? '';
                    }
                )
                ->searchable()
                ->sortable(),
            Column::make("All Debt")
                ->label(
                    function ($row) {
                        $debt = $row->principal_amount + $row->penalty_amount + $row->interest_amount;
                        return number_format($debt, 2);
                    }
                )
                ->searchable()
                ->sortable(),
            Column::make("Principal Amount")
                ->label(
                    function ($row) {
                        return number_format($row->principal_amount, 2);
                    }
                )
                ->searchable()
                ->sortable(),
            Column::make("Pelnaty & Interest")
                ->label(
                    function ($row) {
                        $debt = $row->penalty_amount + $row->interest_amount;
                        return number_format($debt, 2);
                    }
                )
                ->searchable()
                ->sortable(),
            Column::make("OutStanding Amount")
                ->label(
                    function ($row) {
                        return number_format($row->outstanding_amount, 2);
                    }
                )
                ->searchable()
                ->sortable(),


            Column::make("Tax In Dispute")
                ->label(
                    function ($row) {
                        return number_format($row->tax_in_dispute, 2);
                    }
                )
                ->searchable()
                ->sortable(),
            Column::make("Tax not in Dispute")
                ->label(
                    function ($row) {
                        return number_format($row->tax_not_in_dispute, 2);
                    }
                )
                ->searchable()
                ->sortable(),
            Column::make("Tax Deposit")
                ->label(
                    function ($row) {
                        return number_format($row->tax_in_deposit, 2);
                    }
                )
                ->searchable()
                ->sortable(),

        ];
    }
}
