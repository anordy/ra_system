<?php

namespace App\Http\Livewire\Returns\FinancialMonths;

use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\TaPaymentConfiguration;

class FinancialMonthsTable extends DataTableComponent
{
    public $today;
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
        $day = date('Y-m-d');
        $day = date('F', strtotime($day));
        $year = FinancialYear::query()->where('code',date('Y'))->first();
        $today = FinancialMonth::query()->where('financial_year_id', $year->id)->where('name', $day)->first();
        $this->today = $today->id;
        return FinancialMonth::query()->orderBy('financial_months.id', 'desc');
    }


    public function columns(): array
    {

        return [
            Column::make("Month", "name")
                ->sortable()->searchable(),
            Column::make("year", "year.code")
                ->sortable()->searchable(),
            Column::make("Normal Due Date", "due_date")
                ->sortable()->searchable(),
            Column::make("Created At", "created_at")
                ->sortable()->searchable(),
            Column::make("Action", "id")
                ->format(function ($value, $row) {
                    if ($this->today == $value){
                    return <<< HTML
                    <button class="btn btn-success btn-sm" onclick="Livewire.emit('showModal', 'returns.financial-months.extend-month-modal',$value)"><i class="fa fa-edit mr-1"></i>Extend</button>
                HTML;}
                })
                ->html(true),
        ];
    }
}
