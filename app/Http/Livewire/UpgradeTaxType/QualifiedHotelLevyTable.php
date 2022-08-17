<?php

namespace App\Http\Livewire\Debt;

use App\Models\Returns\HotelReturns\HotelReturnConfig;
use App\Models\Returns\HotelReturns\HotelReturnItem;
use App\Models\Returns\ReturnStatus;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class QualifiedHotelLevyTable extends DataTableComponent
{
    use LivewireAlert;

    public function builder(): Builder
    {
        $salesConfigs = HotelReturnConfig::query()->whereIn('code', ['HS', 'RS', 'TOS', 'OS'])->get()->pluck('id');

        return HotelReturnItem::query()
            ->selectRaw('financial_months.name as month, 
            financial_years.code as year, SUM(value) as total_sales, return_id')
            ->leftJoin('hotel_return_configs', 'hotel_return_configs.id', 'hotel_return_items.config_id')
            ->leftJoin('hotel_returns', 'hotel_returns.id', 'hotel_return_items.return_id')
            ->leftJoin('financial_months', 'financial_months.id', 'hotel_returns.financial_month_id')
            ->leftJoin('financial_years', 'financial_years.id', 'financial_months.financial_year_id')
            ->where('hotel_returns.status', ReturnStatus::COMPLETE)
            ->whereIn('config_id', $salesConfigs)
            ->groupBy(['financial_years.code', 'financial_months.name', 'return_id']);

    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['return_id', 'assessment_id', 'penalty_amount', 'interest_amount', 'principal_amount']);

    }

    public function columns(): array
    {
        return [
            Column::make('Month', 'month')
                ->sortable()
                ->searchable(),
            Column::make('Year', 'year')
                ->sortable()
                ->searchable(),


        ];
    }

}
