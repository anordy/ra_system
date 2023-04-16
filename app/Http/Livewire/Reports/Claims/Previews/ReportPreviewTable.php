<?php

namespace App\Http\Livewire\Reports\Claims\Previews;

use App\Traits\ClaimReportTrait;
use App\Traits\WithSearch;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

use App\Traits\ReturnReportTrait;

class ReportPreviewTable extends DataTableComponent
{
    use CustomAlert, ClaimReportTrait;

    public $parameters;

    public function mount($parameters)
    {
        $this->parameters = $parameters;
    }

    public function builder(): Builder
    {
        $reports = $this->getRecords($this->parameters);
        return $reports;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['tax_claims.business_id', 'tax_claims.location_id', 'tax_claims.financial_month_id', 'tax_claims.created_at']);
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

            Column::make("Reporting Month", "financial_month_id")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return $row->financialMonth->name;
                    }
                ),

            Column::make("Currency", "currency")
                ->searchable()
                ->sortable(),

            Column::make("Amount", "amount")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return number_format($value, 2) ?? '-';
                    }
                ),

            Column::make("Claim Status", "status")
                ->searchable()
                ->sortable()
                ->view('reports.claims.includes.claim-status'),

//            Column::make("Payment Status", "paid_at")
//                ->searchable()
//                ->sortable()
//                ->view('reports.returns.includes.payment-status'),
        ];
    }
}
