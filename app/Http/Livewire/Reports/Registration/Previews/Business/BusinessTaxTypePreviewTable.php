<?php

namespace App\Http\Livewire\Reports\Registration\Previews\Business;

use App\Models\TaxType;
use App\Traits\RegistrationReportTrait;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class BusinessTaxTypePreviewTable extends DataTableComponent
{
    use LivewireAlert,RegistrationReportTrait;

    public $taxType;

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setTableWrapperAttributes([
            'default' => true,
            'class'   => 'table-bordered table-sm',
        ]);
        // $this->setAdditionalSelects(['business_locations.business_id']);
    }

    public function mount($tax_type_id){
        $this->taxType = TaxType::find($tax_type_id);
    }

    public function builder(): Builder
    {
        return $this->businessByNatureQuery($this->taxType->id);
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

            Column::make('Tax Type', 'id')
            ->sortable()
            ->searchable()
            ->format(
                function ($value, $row) {
                    return $this->taxType->name;
                }
            ),

            Column::make('TIN', 'business.tin')
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
        ];
    }

}
