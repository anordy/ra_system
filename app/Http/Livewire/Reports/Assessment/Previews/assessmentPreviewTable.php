<?php

namespace App\Http\Livewire\Reports\Assessment\Previews;

use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxType;
use App\Traits\AssessmentReportTrait;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AssessmentPreviewTable extends DataTableComponent
{
    use LivewireAlert ,AssessmentReportTrait;

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
        $this->setAdditionalSelects(['tax_assessments.business_id', 'tax_assessments.location_id',  'tax_assessments.created_at','tax_assessments.principal_amount','tax_assessments.interest_amount','tax_assessments.penalty_amount' ,'tax_assessments.currency','tax_assessments.outstanding_amount' ]);
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
            Column::make("Taxpayer Name", "business_id")
                ->format(
                    function ($value, $row) {
                        return $row->business->owner_designation;
                    }
                )
                ->searchable()
                ->sortable(),
            Column::make("Business Location", "location_id")
                ->format(
                    function ($value, $row) {
                        return $row->location->name;
                    }
                )
                ->searchable()
                ->sortable(),
             Column::make("Currency", "id")
                ->format(
                    function ($value, $row) {
                        return $row->currency;
                    }
                )
                ->searchable()
                ->sortable(),
            Column::make("All Debt", "id")
                ->format(
                    function ($value, $row) {
                        $debt = $row->principal_amount + $row->penalty_amount + $row->interest_amount;
                        return number_format($debt,2);
                    }
                )
                ->searchable()
                ->sortable(),
            Column::make("Principal Amount", "id")
                ->format(
                    function ($value, $row) {
                        return number_format($row->principal_amount,2);
                    }
                )
                ->searchable()
                ->sortable(),
               Column::make("Pelnaty & Interest", "id")
                ->format(
                    function ($value, $row) {
                        $debt = $row->penalty_amount + $row->interest_amount;
                        return number_format($debt,2);
                    }
                )
                ->searchable()
                ->sortable(),
                  Column::make("OutStanding Amount", "id")
                ->format(
                    function ($value, $row) {
                        
                        return number_format($row->outstanding_amount,2);
                    }
                )
                ->searchable()
                ->sortable(),
            // // //filed by
            // Column::make("Filed By", "id")
            //     ->format(
            //         function ($value, $row) {
            //             return $row->taxpayer->fullName;
            //         }
            //     )
            //     ->searchable()
            //     ->sortable(),
            // //currency
            // Column::make("Currency", "currency")
            //     ->searchable()
            //     ->sortable(),
            // //total_amount_due
            // Column::make("Total Amount Due", "total_amount_due")
            //     ->format(
            //         function ($value, $row) {
            //             return number_format($value, 2);
            //         }
            //     )
            //     ->searchable()
            //     ->sortable(),
            // //total_amount_due_with_penalties
            // Column::make("Total Amount Due With Penalties", "total_amount_due_with_penalties")
            //     ->format(
            //         function ($value, $row) {
            //             return number_format($value, 2);
            //         }
            //     )
            //     ->searchable()
            //     ->sortable(),
            // //filing_due_date
            // Column::make("Filing Due Date", "filing_due_date")
            //     ->format(
            //         function ($value, $row) {
            //             if (!$value) {
            //                 return '-';
            //             }
            //             return date('d/m/Y', strtotime($value));
            //         }
            //     )
            //     ->searchable()
            //     ->sortable(),
            // //file status
            // Column::make("File Status", "id")
            //     ->format(
            //         function ($value, $row) {
            //             if ($row->created_at < $row->filing_due_date) {
            //                 return '<span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
            //                 <i class="bi bi-check-circle"></i>
            //                             In Time
            //                         </span>';
            //             } else {
            //                 return '<span class="badge badge-danger py-1 px-2" style="border-radius: 1rem; background: rgba(220,53,53,0.35); color: #cf1c1c; font-size: 85%">
            //                 <i class="bi bi-clock"></i>
            //                             Late
            //                         </span>';
            //             }
            //         }
            //     )
            //     ->searchable()
            //     ->sortable()
            //     ->html(),
            // //payment_due_date
            // Column::make("Payment Due Date", "payment_due_date")
            //     ->format(
            //         function ($value, $row) {
            //             return date('d/m/Y', strtotime($value));
            //         }
            //     )
            //     ->searchable()
            //     ->sortable(),
            // //payment status
            // Column::make("Payment Status", "paid_at")
            //     ->format(
            //         function ($value, $row) {
            //             if ($row->created_at == null || $row->paid_at == null) {
            //                 return '-';
            //             } else {
            //                 if ($row->paid_at < $row->payment_due_date) {
            //                     return '<span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
            //                     <i class="bi bi-check-circle"></i>
            //                         In-Time
            //                             </span>';
            //                 } else {
            //                     return '<span class="badge badge-danger py-1 px-2" style="border-radius: 1rem; background: rgba(220,53,53,0.35); color: #cf1c1c; font-size: 85%">
            //                     <i class="bi bi-clock"></i>
            //                                 Late
            //                             </span>';
            //                 }
            //             }

            //         }
            //     )
            //     ->searchable()
            //     ->sortable()
            //     ->html(),
        ];
    }
}
