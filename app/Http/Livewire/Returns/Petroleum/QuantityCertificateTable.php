<?php

namespace App\Http\Livewire\Returns\Petroleum;

use App\Models\QuantityCertificate;
use App\Models\Returns\Petroleum\PetroleumReturn;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;

class QuantityCertificateTable extends DataTableComponent
{
    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function builder(): Builder
    {
        return QuantityCertificate::query()->where('created_by', auth()->user()->id);
    }


    public function columns(): array
    {
        return [
            Column::make('Name of Importer/Market', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Ship Name', 'ship')
                ->sortable()
                ->searchable(),
            Column::make('Cargo Discharged', 'cargo')
                ->sortable()
                ->searchable(),
            Column::make('Port of Disembarkation', 'liters_observed')
                ->format(fn ($value) => number_format($value, 3))
                ->sortable()
                ->searchable(),
            Column::make('Quantity (ltrs) Observed', 'liters_observed')
                ->format(fn ($value) => number_format($value, 3))
                ->sortable()
                ->searchable(),
            Column::make('Quantity (ltrs) @20 Degree', 'liters_at_20')
                ->format(fn ($value) => number_format($value, 3))
                ->sortable()
                ->searchable(),
            Column::make('Metric Tons', 'metric_tons')
                ->sortable()
                ->searchable(),
            Column::make('Action', 'id')->view('returns.petroleum.quantity_certificate.includes.actions'),

        ];
    }
}
