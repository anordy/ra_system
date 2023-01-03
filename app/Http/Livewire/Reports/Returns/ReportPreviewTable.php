<?php

namespace App\Http\Livewire\Reports\Returns;

use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Models\TaxType;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

use App\Traits\ReturnReportTrait;

class ReportPreviewTable extends DataTableComponent
{
    use LivewireAlert, ReturnReportTrait;

    public $parameters;
    public $lumpsump;

    public function mount($parameters)
    {
        $this->parameters = $parameters;
        $this->lumpsump = TaxType::where('code', TaxType::LUMPSUM_PAYMENT)->first();
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
        $this->setAdditionalSelects(['tax_returns.business_id', 'tax_returns.location_id', 'tax_returns.financial_month_id', 'tax_returns.created_at', 'tax_returns.filed_by_id', 'tax_returns.filed_by_type', 'tax_returns.return_id']);
    }

    public function columns(): array
    {
        return [
            Column::make("Filing Date")
                ->searchable()
                ->sortable()
                ->label(fn($row) => date('M, d Y', strtotime($row->created_at)) ?? ''),

            Column::make("Business")
                ->searchable()
                ->sortable()
                ->label(fn($row) => $row->business->name ?? ''),

            Column::make("Business Location")
                ->searchable()
                ->sortable()
                ->label(fn($row) => $row->location->name ?? ''),

            Column::make("Tax Type")
                ->searchable()
                ->sortable()
                ->label(fn($row) => $row->taxtype->code ?? ''),

            Column::make("Reporting Month")
                ->searchable()
                ->sortable()
                ->label(fn($row) => $row->tax_type_id == $this->lumpsump->id ? LumpSumReturn::where('id', $row->return_id)->first()->quarter_name : $row->financialMonth->name ?? ''),

            Column::make("Filed By")
                ->searchable()
                ->sortable()
                ->label(fn($row) => $row->taxpayer->fullName ?? ''),

            Column::make("Currency")
                ->searchable()
                ->sortable()
                ->label(fn($row) => $row->currency,2 ?? ''),

            Column::make("Principal Amount")
                ->searchable()
                ->sortable()
                ->label(fn($row) => $row->principal,2 ?? ''),

            Column::make("Interest")
                ->searchable()
                ->sortable()
                ->label(fn($row) => $row->interest ?? ''),

            Column::make("Penalty")
                ->searchable()
                ->sortable()
                ->label(fn($row) => $row->penalty ?? ''),

            Column::make("Total Amount")
                ->searchable()
                ->sortable()
                ->label(fn($row) => $row->total_amount ?? ''),

            Column::make("Outstanding Amount")
                ->searchable()
                ->sortable()
                ->label(fn($row) => $row->outstanding_amount ?? ''),

            Column::make("Filing Due Date")
                ->searchable()
                ->sortable()
                ->label(fn($row) => date('M, d Y', strtotime($row->filing_due_date)) ?? ''),

            Column::make("Filing Status", "id")
                ->searchable()
                ->sortable()
                ->view('reports.returns.includes.filing-status'),

            Column::make("Payment Status", "paid_at")
                ->searchable()
                ->sortable()
                ->view('reports.returns.includes.payment-status'),
        ];
    }
}
