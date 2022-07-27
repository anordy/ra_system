<?php

namespace App\Http\Livewire\Settings\HotelLevyReturns;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\HotelLevyConfig;

class HotelLevyConfigTable extends DataTableComponent
{
    protected $model = HotelLevyConfig::class;

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
        $this->setAdditionalSelects(['rate_in_percentage', 'rate_in_amount']);
    }

    public function columns(): array
    {
        return [
            Column::make("Name", "name")
                ->sortable(),
            Column::make("Code", "code")
                ->searchable(),
            Column::make("Rate", "is_rate_in_percentage")
                ->format(function ($value, $row) {
                    if ($value === 1) {
                        return $row->rate_in_percentage == null ? 'NULL' : "{$row->rate_in_percentage}%";
                    } else {
                        return "{$row->rate_in_amount} USD";
                    }
                })->html(true),
            Column::make("Status", "status")
                ->searchable(),
            Column::make("Financial Year", "financial_year")
                ->searchable(),
            // Column::make('Status', 'status')->view('business.branches.includes.status'),
            // Column::make('Action', 'id')->view('business.branches.includes.actions'),
        ];
    }
}
