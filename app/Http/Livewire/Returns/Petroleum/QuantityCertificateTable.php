<?php

namespace App\Http\Livewire\Returns\Petroleum;

use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Returns\Petroleum\QuantityCertificate;
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
            Column::make('Port of Disembarkation', 'port')
                ->sortable()
                ->searchable(),
            Column::make('Ascertained Date', 'ascertained')
                ->sortable()
                ->searchable(),
            Column::make('Status', 'status')
                ->sortable()
                ->searchable(),
            Column::make('Action', 'id')->view('returns.petroleum.quantity_certificate.includes.actions'),

        ];
    }
}
