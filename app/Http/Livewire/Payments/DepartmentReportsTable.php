<?php

namespace App\Http\Livewire\Payments;

use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Models\TaxType;
use App\Traits\DepartmentalReportTrait;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

use App\Traits\ReturnReportTrait;

class DepartmentReportsTable extends DataTableComponent
{
    use LivewireAlert, DepartmentalReportTrait;

    public $parameters, $lumpsump;

    public function mount($parameters)
    {
        $this->parameters = $parameters;
        $this->lumpsump = TaxType::where('code', TaxType::LUMPSUM_PAYMENT)->first();
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
        $this->setAdditionalSelects(['tax_returns.business_id', 'tax_returns.location_id', 'tax_returns.financial_month_id', 'tax_returns.created_at', 'tax_returns.filed_by_id', 'tax_returns.filed_by_type', 'tax_returns.return_id']);
    }

    public function columns(): array
    {
        return [
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

            Column::make("Currency",'currency')
                ->searchable()
                ->sortable()
                 ->label(fn($row) => $row->currency ?? ''),

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

            Column::make("Payment Status", "paid_at")
                ->searchable()
                ->sortable()
                ->view('reports.returns.includes.payment-status'),
        ];
    }
}
