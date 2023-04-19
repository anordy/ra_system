<?php

namespace App\Http\Livewire\Reports\Business;

use App\Models\Business;
use App\Traits\RegistrationReportTrait;
use App\Traits\WithSearch;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PreviewTable extends DataTableComponent
{
    use CustomAlert, RegistrationReportTrait;

    public $parameters;

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['business_locations.business_id', 'business_locations.tax_region_id']);
    }

    public function mount($parameters)
    {
        $this->parameters = $parameters;
    }

    public function builder(): Builder
    {
        return $this->getBusinessBuilder($this->parameters);
    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Branch', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Tax Region', 'taxRegion.name')
                ->sortable()
                ->searchable(),
            Column::make('TIN', 'business.tin')
                ->sortable()
                ->searchable(),
            Column::make('Zin No.', 'zin')
                ->sortable()
                ->searchable(),
            Column::make('Business Category', 'business.business_category_id')
                ->sortable()
                ->searchable()
                ->format(
                    function ($value, $row) {
                        return $row->business->category->name;
                    }
                ),
            Column::make('Taxpayer', 'business.taxpayer_id')
                ->sortable()
                ->searchable()
                ->format(
                    function ($value, $row) {
                        return $row->business->taxpayer->fullname;
                    }
                ),
            Column::make('Date of Commensing', 'date_of_commencing')
                ->sortable()
                ->searchable()
                ->format(
                    function ($value, $row) {
                        return date('M, d Y', strtotime($row->date_of_commencing));
                    }
            ),
            Column::make('Region', 'region_id')
                ->sortable()
                ->searchable()
                ->format(
                    function ($value, $row) {
                        return $row->region->name ?? '-';
                    }
            ),
            Column::make('District', 'district_id')
                ->sortable()
                ->searchable()
                ->format(
                    function ($value, $row) {
                        return $row->district->name ?? '-';
                    }
            ),
            Column::make('Ward', 'ward_id')
                ->sortable()
                ->searchable()
                ->format(
                    function ($value, $row) {
                        return $row->region->name ?? '-';
                    }
            ),
            Column::make('Physical Address', 'physical_address')
                ->sortable()
                ->searchable(),

            Column::make('Status', 'business.status')
                ->view('reports.business.includes.status'),
        ];
    }

}
