<?php

namespace App\Http\Livewire\Reports\Returns\Previews;

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
        // dd($parameters);
        $this->parameters = $parameters;
        $this->lumpsump = TaxType::where('code', TaxType::LUMPSUM_PAYMENT)->first();
    }

    public function builder(): Builder
    {
        $mnos = $this->getRecords($this->parameters);
        // dd($mnos->get());
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
            Column::make("Filing Date", "created_at")
                ->searchable()
                ->sortable()
                ->format(function ($value, $row) {
                    return date('d/m/Y', strtotime($value));
                }),

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
                        if ($row->tax_type_id == $this->lumpsump->id) {
                            return LumpSumReturn::where('id', $row->return_id)->first()->quarter_name ?? '-';
                        } else {
                            return $row->financialMonth->name;
                        }
                    }
                ),

            Column::make("Filed By", "id")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return $row->taxpayer->fullName;
                    }
                ),

            Column::make("Currency", "currency")
                ->searchable()
                ->sortable(),

            Column::make("Principal Amount", "principal")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return number_format($value, 2) ?? '-';
                    }
                ),

            Column::make("Interest", "interest")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return number_format($value, 2) ?? '-';
                    }
                ),

            Column::make("Penalty", "penalty")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return number_format($value, 2) ?? '-';
                    }
                ),

            Column::make("Total Amount", "total_amount")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return number_format($value, 2);
                    }
                ),

            Column::make("Outstanding Amount", "outstanding_amount")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return number_format($value, 2);
                    }
                ),

            Column::make("Filing Due Date", "filing_due_date")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        if (!$value) {
                            return '-';
                        }
                        return date('M, d Y', strtotime($value));
                    }
                ),

            Column::make("Filing Status", "created_at")
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
