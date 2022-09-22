<?php

namespace App\Http\Livewire\Reports\Debts\Previews;

use App\Models\TaxType;
use App\Traits\DebtReportTrait;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;


class ReturnDebtReportPreviewTable extends DataTableComponent
{
    use LivewireAlert, DebtReportTrait;

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

            Column::make("Currency", "currency")
                ->searchable()
                ->sortable(),

            Column::make("Actual Amount", "total_amount")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return number_format($value, 2);
                    }
                ),

                Column::make("Accumulated Amount", "total_amount")
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

            Column::make("Payment Due Date", "payment_due_date")
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
                Column::make("Status", "application_status")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return $value;
                    }
                ),
        ];
    }
}
