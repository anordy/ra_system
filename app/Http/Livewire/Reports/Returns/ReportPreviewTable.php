<?php

namespace App\Http\Livewire\Reports\Returns;

use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Models\TaxType;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

use App\Traits\ReturnReportTrait;

class ReportPreviewTable extends DataTableComponent
{
    use CustomAlert, ReturnReportTrait;

    public $parameters, $lumpsump;

    public function mount($parameters)
    {
        $this->parameters = $parameters;
        $this->lumpsump = TaxType::select('id', 'code')->where('code', TaxType::LUMPSUM_PAYMENT)->first();
    }

    public function builder(): Builder
    {
        return $this->getRecords($this->parameters);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['tax_returns.business_id', 'tax_returns.location_id', 'tax_returns.financial_month_id', 'tax_returns.created_at', 'tax_returns.filed_by_id', 'tax_returns.filed_by_type', 'tax_returns.return_id', 'tax_returns.curr_payment_due_date']);
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

            Column::make("Tax Type",'tax_type_id')
                ->searchable()
                ->sortable()
                ->format(
                    function ($value) {
                        return TaxType::find($value)->name ?? '';
                    }
                ),

            // Column::make("Reporting Month")
            //     ->searchable()
            //     ->sortable()
            //     ->label(fn($row) => $row->tax_type_id == $this->lumpsump->id ? LumpSumReturn::where('id', $row->return_id)->first()->quarter_name : $row->financialMonth->name ?? ''),

            Column::make("Filed By")
                ->searchable()
                ->sortable()
                ->label(fn($row) => $row->taxpayer->fullName ?? ''),

            Column::make("Currency",'currency')
                ->searchable()
                ->sortable(),
                
            Column::make("Principal Amount",'principal')
                ->searchable()
                ->sortable()
                ->format(
                    function ($value) {
                        return number_format($value,2);
                    }
                ),

            Column::make("Interest",'interest')
                ->searchable()
                ->sortable()
                ->format(
                    function ($value) {
                        return number_format($value,2);
                    }
                ),

            Column::make("Penalty",'penalty')
                ->searchable()
                ->sortable()
                ->format(
                    function ($value) {
                        return number_format($value,2);
                    }
                ),

            Column::make("Total Amount",'total_amount')
                ->searchable()
                ->sortable()
                ->format(
                    function ($value) {
                        return number_format($value,2);
                    }
                ),

            Column::make("Outstanding Amount",'outstanding_amount')
                ->searchable()
                ->sortable()
                ->format(
                    function ($value) {
                        return number_format($value,2);
                    }
                ),

            Column::make("Filing Due Date", 'filing_due_date')
                ->searchable()
                ->sortable()
                ->format(fn($value) => \Carbon\Carbon::create($value)->addMonth()->format('d/m/Y') ?? 'N/A'),

            Column::make("Filing Status", "id")
                ->searchable()
                ->sortable()
                ->view('reports.returns.includes.filing-status'),

            Column::make("Payment Status", 'paid_at')
                ->format(function ($value, $row) {
                    if ($row->paid_at == null) {
                        return <<< HTML
                            <span class="badge badge-danger">Not Paid</span>
                    HTML;
                    } else if ($row->paid_at > $row->curr_payment_due_date) {
                        return <<< HTML
                        <span class="badge badge-warning">Late Payment</span>
                    HTML;
                    } else if ($row->paid_at <= $row->curr_payment_due_date) {
                        return <<< HTML
                        <span class="badge badge-success">Paid</span>
                    HTML;
                    }
                })
                ->html()
        ];
    }
}