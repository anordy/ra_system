<?php

namespace App\Http\Livewire\PropertyTax;

use App\Models\PropertyTax\Condominium;
use App\Models\PropertyTax\Property;
use App\Traits\CustomAlert;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ApprovalTable extends DataTableComponent
{
    use CustomAlert;

    public function builder(): Builder
    {
        return Property::orderByDesc('created_at');
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
            Column::make('Date of Registration', 'created_at')
                ->format(function ($value, $row) {
                    return $row->created_at->toFormattedDateString() ?? 'N/A';
                }),
            Column::make('Status', 'status')
                ->view('property-tax.includes.status'),
            Column::make('Action', 'id')
                ->view('property-tax.includes.actions'),
        ];
    }

}
