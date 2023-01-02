<?php

namespace App\Http\Livewire\Debt;

use Carbon\Carbon;
use App\Enum\ReturnCategory;
use Illuminate\Database\Eloquent\Builder;
use App\Models\TaxAssessments\TaxAssessment;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class OverdueAssessmentDebtsTable extends DataTableComponent
{

    use LivewireAlert;

    public function builder(): Builder
    {
        return TaxAssessment::where('assessment_step', ReturnCategory::OVERDUE)->orderBy('tax_assessments.created_at', 'desc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['tax_assessments.business_id', 'tax_type_id', 'tax_assessments.location_id']);
    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Location', 'location.name')
                ->sortable()
                ->searchable(),
            Column::make('Tax Type', 'taxtype.name'),
            Column::make('Principal', 'principal_amount')
                ->format(function ($value, $row) {
                    return number_format($row->principal_amount, 2);
                }),
            Column::make('Penalty', 'penalty_amount')
                ->format(function ($value, $row) {
                    return number_format($row->penalty_amount, 2);
                }),
            Column::make('Interest', 'interest_amount')
                ->format(function ($value, $row) {
                    return number_format($row->interest_amount, 2);
                }),
            Column::make('Total', 'total_amount')
                ->format(function ($value, $row) {
                    return number_format($row->total_amount, 2);
                }),
            Column::make('Days', 'payment_due_date')
                ->format(function ($value, $row) {
                    return Carbon::now()->diffInDays($row->payment_due_date);
                }),
            Column::make('Status', 'payment_status')->view('debts.assessments.includes.status'),
            Column::make('Actions', 'id')->view('debts.assessments.includes.actions'),
        ];
    }
}
