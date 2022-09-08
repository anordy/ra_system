<?php

namespace App\Http\Livewire\Returns\FinancialMonths;

use App\Models\FinancialMonth;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\TaPaymentConfiguration;

class FinancialMonthsTable extends DataTableComponent
{
//    protected $model = TaPaymentConfiguration::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['financial_year_id']);

    }

    public function builder(): Builder
    {
        return FinancialMonth::query()->orderBy('financial_months.id', 'desc');
    }


    public function columns(): array
    {

        return [
            Column::make("Name", "name")
                ->sortable()->searchable(),
            Column::make("year", "year.code")
                ->sortable()->searchable(),
            Column::make("Normal Due Date", "due_date")
                ->sortable()->searchable(),
            Column::make("lumpsum Due Date", "lumpsum_due_date")
                ->sortable()->searchable(),
            Column::make("Created At", "created_at")
                ->sortable()->searchable(),
//            Column::make("duration", "duration")
//                ->view('taxagents.includes.duration'),
//            Column::make('Amount', 'amount')
//                ->format(
//                    fn($value, $row, Column $column) => number_format($row->amount, '2', '.', ',') . '<strong> Tsh</strong>'
//                )
//                ->html()->searchable(),
            Column::make("Created At", "created_at")
                ->sortable()->searchable(),
//            Column::make("No of days/months/years", "no_of_days")
//                ->view('taxagents.includes.no_of_days'),
        ];
    }
}
