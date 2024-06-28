<?php

namespace App\Http\Livewire\UpgradeTaxType;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\BusinessTaxTypeChange;

class UpgradedTaxTypeTable extends DataTableComponent
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
        return BusinessTaxTypeChange::query()->where('category','qualified');
    }

    public function columns(): array
    {
        return [
            Column::make("Business Name", "business.name")
                ->sortable(),
            Column::make("From Tax Type", "from_tax_type_id")
                ->sortable()
                ->format(function ($value, $row) {
                    return $row->fromTax ? $row->fromTax->name : 'N/A';
                }),
            Column::make("To Tax Type", "to_tax_type_id")
                ->searchable()
                ->format(function ($value, $row) {
                    return $row->toTax ? $row->toTax->name : 'N/A';
                }),
            Column::make("Date Upgraded", "created_at")
                ->format(function ($value, $row) {
                    return Carbon::create($value)->format('M d, Y');
                })
                ->searchable(),
            Column::make('Status', 'status')->view('upgrade-tax-type.upgraded.includes.status'),
            Column::make('Action', 'id')->view('upgrade-tax-type.upgraded.includes.actions'),
        ];
    }
}
