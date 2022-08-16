<?php

namespace App\Http\Livewire\Debt;

use App\Enum\TaxAssessmentStatus;
use App\Models\TaxAssessments\TaxAssessment;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class AssesmentsTable extends DataTableComponent
{
    use LivewireAlert;

    public function builder(): Builder
    {
        return TaxAssessment::query();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['tax_type_id', 'assessment_id', 'penalty_amount', 'interest_amount', 'principal_amount']);

    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Business Name', 'location.name')
                ->sortable()
                ->searchable(),
            Column::make('Tax Type', 'taxtype.name'),
            Column::make('Total Debt', 'assessment_id')
                ->format(function ($value, $row) {
                    return number_format($row->principal_amount + $row->penalty_amount + $row->interest_amount);
                }),
            // Column::make('Date of Notice Assessment', 'assesment.created_at')
            // ->format(function ($value, $row) {
            //     return Carbon::create($value)->format('M d, Y');
            // }),
            // Column::make('Action', 'id')->view('debts.verifications.includes.actions'),

        ];
    }

}
