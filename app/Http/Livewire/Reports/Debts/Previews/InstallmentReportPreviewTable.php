<?php

namespace App\Http\Livewire\Reports\Debts\Previews;

use App\Traits\DebtReportTrait;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;


class InstallmentReportPreviewTable extends DataTableComponent
{
    use CustomAlert, DebtReportTrait;

    public $parameters;

    public function mount($parameters)
    {
        $this->parameters = $parameters;
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
        $this->setAdditionalSelects(['installments.installable_id', 'installable_type', 'amount', 'currency']);
    }

    public function columns(): array
    {
        return [
            Column::make('installable_id', 'installable_type')->hideIf(true),

            Column::make("Business", "id")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return $row->installable->business->name;
                    }
                ),

            Column::make("Business Location", "id")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return $row->installable->location->name;
                    }
                ),

            Column::make("Tax Type", "id")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return $row->installable->taxType->name;
                    }
                ),

            Column::make("Start Date", "installment_from")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value) {
                        return $value->toDateString();
                    }
                ),

            Column::make("End Date", "installment_to")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value) {
                        return $value->toDateString();
                    }
                ),

            Column::make("Inst. No.", "installment_count")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value) {
                        return $value;
                    }
                ),

            Column::make("Total Amount", "id")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return number_format($row->amount, 2) . ' ' . $row->currency;
                    }
                ),

            Column::make("Amount per Installment", "id")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return number_format($row->amount / $row->installment_count, 2) . ' ' . $row->currency;
                    }
                ),

            Column::make("Paid Amount", "id")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return number_format($row->paidAmount(), 2) . ' ' . $row->currency;
                    }
                ),

            Column::make("Outstanding Amount", "id")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return number_format($row->amount - $row->paidAmount(), 2) . ' ' . $row->currency;
                    }
                ),
        ];
    }
}
