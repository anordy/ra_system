<?php

namespace App\Http\Livewire\PropertyTax\Condominium;

use App\Models\PropertyTax\Condominium;
use App\Traits\CustomAlert;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RegisteredCondominiumsTable extends DataTableComponent
{
    use CustomAlert;

    public function builder(): Builder
    {
        return Condominium::orderByDesc('created_at');
    }
    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->searchable(),
            Column::make('Region', 'region_id')
                ->searchable()
                ->format(function ($value, $row) {
                    return $row->region->name ?? 'N/A';
                }),
            Column::make('District', 'district_id')
                ->searchable()
                ->format(function ($value, $row) {
                    return $row->district->name ?? 'N/A';
                }),
            Column::make('Ward', 'ward_id')
                ->searchable()
                ->format(function ($value, $row) {
                    return $row->ward->name ?? 'N/A';
                }),
            Column::make('Street', 'street_id')
                ->searchable()
                ->format(function ($value, $row) {
                    return $row->street->name ?? 'N/A';
                }),
            Column::make('Storeys Number')
                ->label(function ($row) {
                    return $row->storeys->count();
                }),
            Column::make('Units Number')
                ->label(function ($row) {
                    return $row->units->count();
                }),
            Column::make('Date of Registration', 'created_at')
                ->format(function ($value, $row) {
                    return $row->created_at->toFormattedDateString() ?? 'N/A';
                }),
            Column::make('Status', 'status')
                ->view('property-tax.condominium.includes.status'),
            Column::make('Action', 'id')
                ->view('property-tax.condominium.includes.actions'),
        ];
    }

}
