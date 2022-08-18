<?php

namespace App\Http\Livewire\Debt;

use App\Models\Business;
use App\Models\Debts\Debt;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class AssessmentDebtsTable extends DataTableComponent
{

    use LivewireAlert;

    public function builder(): Builder
    {
        return Debt::query()->where('debt_type', 'App\Models\TaxAssessments\TaxAssessment');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['business_id','tax_type_id', 'business_location_id']);

    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Tax Payer', 'business.taxpayer.first_name')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->business->taxpayer->first_name} {$row->business->taxpayer->last_name}";
                }),
            Column::make('Tax Type', 'taxtype.name'),
            Column::make('Penalty', 'penalty')
                ->format(function ($value, $row) {
                    return number_format($row->penalty, 2);
                }),
            Column::make('Interest', 'interest')
                ->format(function ($value, $row) {
                    return number_format($row->interest, 2);
                }),
            Column::make('Total Debt', 'total_amount')
                ->format(function ($value, $row) {
                    return number_format($row->total_amount, 2);
                }),
            Column::make('Status', 'app_step')->view('debts.includes.status'),
        ];
    }
}
