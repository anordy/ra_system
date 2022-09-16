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
    use LivewireAlert ,DisputeReportTrait;

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
        $this->setAdditionalSelects(['disputes.business_id', 'disputes.location_id',  'disputes.created_at','disputes.tax_in_dispute','disputes.tax_not_in_dispute','disputes.tax_deposit' ]);
    }

    public function columns(): array
    {
        return [
            Column::make("Register Date", "created_at")
                ->format(function ($value, $row) {
                    return date('d/m/Y', strtotime($value));
                })
                ->searchable()
                ->sortable()
                ->hideIf(true),
            Column::make("Taxpayer", "business_id")
                ->format(
                    function ($value, $row) {
                        return $row->business->owner_designation;
                    }
                )
                ->searchable()
                ->sortable(),
            // Column::make("Business Location", "location_id")
            //     ->format(
            //         function ($value, $row) {
            //             return $row->location->name;
            //         }
            //     )
            //     ->searchable()
            //     ->sortable(),
           
            Column::make("Tax In Dispute", "id")
                ->format(
                    function ($value, $row) {
                        return number_format($row->tax_in_dispute,2);
                    }
                )
                ->searchable()
                ->sortable(),
            Column::make("Tax not in Dispute", "id")
                ->format(
                    function ($value, $row) {
                        return number_format($row->tax_not_in_dispute,2);
                    }
                )
                ->searchable()
                ->sortable(),
               Column::make("Tax Deposit", "id")
                ->format(
                    function ($value, $row) {
                        return number_format($row->tax_in_deposit,2);
                    }
                )
                ->searchable()
                ->sortable(),
                 
        ];
    }
}
