<?php

namespace App\Http\Livewire\Investigation;

use App\Models\PartialPayment;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxInvestigationAssessmentPaymentsTable extends DataTableComponent
{

    public function builder(): Builder
    {
        return PartialPayment::query()
            ->with('taxAssessment.business')
            ->has('taxAssessment');
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
            Column::make('ZTN No', 'taxassessment.business.ztn_number')
                ->sortable()
                ->searchable(),
            Column::make('TIN', 'taxassessment.business.tin')
                ->sortable()
                ->searchable(),
            Column::make('Business Name', 'taxassessment.business.name')
                ->sortable()
                ->searchable(),
            Column::make('Amount', 'amount')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                }),
            Column::make('Assesment Type', 'taxassessment.assessment_type')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    $row = explode('\\', $value);
                    $row = preg_replace("/(?<!^)([A-Z])/", ' $1', $row);
                    return end($row);
                }),
            Column::make('Payment Status', 'payment_status')
                ->view('investigation.assessment-payments.includes.status')
                ->sortable()
                ->searchable(),
            Column::make('Application Status', 'status')
                ->sortable()
                ->searchable(),
            Column::make('Action', 'id')
                ->view('investigation.assessment-payments.action')
                ->html(),
        ];
    }
}
