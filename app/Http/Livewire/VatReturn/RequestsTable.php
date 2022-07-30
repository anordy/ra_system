<?php

namespace App\Http\Livewire\VatReturn;

use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\VatReturn\VatReturn;
class RequestsTable extends DataTableComponent
{
    public $year, $month;
//    protected $model = VatReturn::class;

//$this->returns = VatReturn::where('created_by', Auth::id())->latest()->first();
    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }


    public function builder(): Builder
    {
        $year = FinancialYear::find($this->year)->name;
        $month = FinancialMonth::where('code', $this->month)->first();
        return VatReturn::query()
            ->where('financial_year', $year)
            ->where('return_month', $month->name)->with('business');
    }

    public function columns(): array
    {
        return [
            Column::make("Business Name", "business.name")
                ->sortable(),
            Column::make("Financial Year", "financial_year")
                ->sortable(),
            Column::make("Return Month", "return_month")
                ->sortable(),
            Column::make("Tax Type", "taxtype_code")
                ->sortable(),
            Column::make("Total Vat Payable", "total_vat_amount_due")
                ->sortable(),
            Column::make("Created At", "created_at")
                ->sortable(),
            Column::make('Action', 'id')
            ->view('vat-return.includes.actions')
        ];
    }
}
