<?php

namespace App\Http\Livewire\PropertyTax;

use App\Enum\PropertyStatus;
use App\Enum\PropertyTypeStatus;
use App\Models\PropertyTax\Property;
use App\Traits\CustomAlert;
use App\Traits\PropertyTaxTrait;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class NextPropertyTaxPaymentTable extends DataTableComponent
{
    use CustomAlert, PropertyTaxTrait;

    public function builder(): Builder
    {
        return Property::where('status', PropertyStatus::APPROVED)
            ->orderByDesc('created_at');
    }
    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['hotel_stars_id']);
    }

    public function columns(): array
    {
        return [
            Column::make('Property Name', 'name')
                ->searchable()
                ->format(function ($value, $row) {
                    if ($row->type != PropertyTypeStatus::CONDOMINIUM) {
                        return $row->name ?? 'N/A';
                    } else {
                        return "{$row->name} - {$row->unit->name}" ?? 'N/A';
                    }
                }),
            Column::make('URN', 'urn')
                ->searchable()
                ->format(function ($value, $row) {
                    return $value ?? 'N/A';
                }),
            Column::make('Type', 'type')
                ->searchable()
                ->format(function ($value, $row) {
                    return formatEnum($value) ?? 'N/A';
                }),
            Column::make('Usage Type', 'usage_type')
                ->searchable()
                ->format(function ($value, $row) {
                    return formatEnum($value) ?? 'N/A';
                }),
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
            Column::make('Billable Amount')
                ->label(function ($row) {
                    $amount = $this->getPayableAmount($row);
                    if ($amount) {
                        return number_format($amount, 2);
                    }
                    return 'N/A';
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
