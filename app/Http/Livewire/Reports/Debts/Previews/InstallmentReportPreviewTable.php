<?php

namespace App\Http\Livewire\Reports\Debts\Previews;

use App\Traits\DebtReportTrait;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;


class InstallmentReportPreviewTable extends DataTableComponent
{
    use LivewireAlert, DebtReportTrait;

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
        $this->setAdditionalSelects(['installments.installable_id', 'installable_type', 'amount']);
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

            Column::make("From", "installment_from")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return $value;
                    }
                ),

            Column::make("To", "installment_to")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return $value;
                    }
                ),

                Column::make("Installment Count", "installment_count")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return $value;
                    }
                ),

            Column::make("Payment per installment", "id")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return number_format($row->amount / $row->installment_count, 2);
                    }
                ),

        ];
    }
}
