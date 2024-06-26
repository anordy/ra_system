<?php

namespace App\Http\Livewire\PropertyTax;

use App\Enum\PropertyStatus;
use App\Enum\PropertyTypeStatus;
use App\Models\PropertyTax\Condominium;
use App\Models\PropertyTax\Property;
use App\Traits\CustomAlert;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PropertyTaxTable extends DataTableComponent
{
    use CustomAlert;
    public $status;
    public function mount($status) {
        $this->status = $status;
    }

    public function builder(): Builder
    {
        return Property::where('status', $this->status)->orderByDesc('created_at');
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
            Column::make('Property Name', 'name')
                ->searchable()
                ->format(function ($value, $row) {
                    if ($row->type != PropertyTypeStatus::CONDOMINIUM) {
                        return $row->name ?? 'N/A';
                    } else {
                        return $row->unit ? "{$row->name} - {$row->unit->name}" : 'N/A';
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
                    return $value ?? 'N/A';
                }),
            Column::make('District', 'district_id')
                ->searchable()
                ->format(function ($value, $row) {
                    return $value ?? 'N/A';
                }),
            Column::make('Ward', 'ward_id')
                ->searchable()
                ->format(function ($value, $row) {
                    return $value ?? 'N/A';
                }),
            Column::make('Responsible Person', 'taxpayer_id')
                ->format(function ($value, $row) {
                    return $row->taxpayer->fullname() ?? 'N/A';
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
