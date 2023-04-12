<?php

namespace App\Http\Livewire\Reports\Debts\Previews;

use App\Traits\DebtReportTrait;
use App\Traits\WithSearch;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;


class DemandNoticeReportPreviewTable extends DataTableComponent
{
    use CustomAlert, DebtReportTrait, WithSearch;

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

                Column::make("Due Date", "id")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return $row->debt->curr_payment_due_date;
                    }
                ),
            Column::make("Paid Within (Days)", "paid_within_days")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return number_format($value);
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

                Column::make("Next Notice Date", "next_notify_date")
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
