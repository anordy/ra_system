<?php

namespace App\Http\Livewire\Reports\Assessment\Previews;

use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxType;
use App\Traits\AssessmentReportTrait;
use App\Traits\WithSearch;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AssessmentPreviewTable extends DataTableComponent
{
    use CustomAlert, AssessmentReportTrait, WithSearch;

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
        $this->setAdditionalSelects(['tax_assessments.business_id', 'tax_assessments.location_id', 'tax_assessments.principal_amount', 'tax_assessments.interest_amount', 'tax_assessments.penalty_amount', 'tax_assessments.currency', 'tax_assessments.outstanding_amount']);
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
            Column::make("Currency")
                ->label(
                    function ($row) {
                        return $row->currency;
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
        ];
    }
}
