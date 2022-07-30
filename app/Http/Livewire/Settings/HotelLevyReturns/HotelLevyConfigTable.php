<?php

namespace App\Http\Livewire\Settings\HotelLevyReturns;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Returns\HotelReturns\HotelReturnConfig;

class HotelLevyConfigTable extends DataTableComponent
{
    protected $model = HotelReturnConfig::class;

    public function mount()
    {
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['rate', 'rate_usd']);
    }

    public function columns(): array
    {
        return [
            Column::make("Name", "name")
                ->sortable(),
            Column::make("Code", "code")
                ->searchable(),
            Column::make("Rate", "rate_type")
                ->format(function ($value, $row) {
                    if ($value === 'percentage') {
                        return $row->rate == null ? 'NULL' : "{$row->rate}%";
                    } else {
                        return "{$row->rate_usd} USD";
                    }
                })->html(true),
            Column::make("Status", "active")
                ->searchable(),
            Column::make("Financial Year", "financia_year_id")
                ->searchable(),
            // Column::make('Status', 'status')->view('business.branches.includes.status'),
            // Column::make('Action', 'id')->view('business.branches.includes.actions'),
        ];
    }
}
