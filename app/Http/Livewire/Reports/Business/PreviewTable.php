<?php

namespace App\Http\Livewire\Reports\Business;

use App\Models\Business;
use App\Traits\RegistrationReportTrait;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PreviewTable extends DataTableComponent
{
    use LivewireAlert, RegistrationReportTrait;

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
        $this->parameters = json_decode(decrypt($parameters), true);
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
            Column::make('TIN', 'business.tin')
                ->sortable()
                ->searchable(),
            Column::make('Zin No.', 'zin')
                ->sortable()
                ->searchable(),
            Column::make('Taxpayer', 'business.taxpayer_id')
                ->sortable()
                ->searchable()
                ->format(
                    function ($value, $row) {
                        return $row->business->taxpayer->fullname;
                    }
                ),
            Column::make('Status', 'business.status')
                ->view('reports.business.includes.status'),
            Column::make('Tax Region', 'taxRegion.name')
                ->sortable()
                ->searchable(),
        ];
    }

}
