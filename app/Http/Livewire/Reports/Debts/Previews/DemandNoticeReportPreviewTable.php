<?php

namespace App\Http\Livewire\Reports\Debts\Previews;

use App\Traits\DebtReportTrait;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;


class DemandNoticeReportPreviewTable extends DataTableComponent
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
        $this->setAdditionalSelects(['demand_notices.debt_id', 'debt_type', 'sent_on', 'paid_within_days']);
    }

    public function columns(): array
    {
        return [
            Column::make('debt_id', 'debt_type')->hideIf(true),

            Column::make("Business", "id")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return $row->debt->business->name;
                    }
                ),

            Column::make("Business Location", "id")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return $row->debt->location->name;
                    }
                ),

            Column::make("Tax Type", "id")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return $row->debt->taxType->name;
                    }
                ),

            Column::make("Currency", "id")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return $row->debt->currency;
                    }
                ),

            Column::make("Paid Within", "paid_within_days")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return number_format($value, 2);
                    }
                ),

                Column::make("Sent On", "sent_on")
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
