<?php

namespace App\Http\Livewire\Reports\Returns\Previews;

use App\Models\Claims\TaxClaim;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

use App\Traits\ReturnReportTrait;

class TaxClaimsPreviewTable extends DataTableComponent
{
    use LivewireAlert, ReturnReportTrait;

    public $parameters;

    public function mount($parameters)
    {
        $this->parameters = $parameters; 
    }

    public function builder(): Builder
    {
        if($this->parameters['dates']['startDate']){
            return TaxClaim::where('tax_type_id',$this->parameters['tax_type_id'])
                            ->where('created_at','>=',$this->parameters['dates']['startDate'])
                            ->where('created_at','<=',$this->parameters['dates']['endDate']);
        }
        return TaxClaim::where('tax_type_id',$this->parameters['tax_type_id']);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['tax_claims.business_id', 'tax_claims.location_id', 'tax_claims.financial_month_id', 'tax_claims.tax_type_id', 'tax_claims.created_at', 'tax_claims.created_by_id', 'tax_claims.created_by_type']);
    }

    public function columns(): array
    {
        return [
            Column::make("Register Date", "created_at")
                ->searchable()
                ->sortable()
                ->format(function ($value, $row) {
                    return date('d/m/Y', strtotime($value));
                }),

            Column::make("Business", "business_id")
                ->searchable()
                ->sortable()
                ->format(
                    function ($value, $row) {
                        return $row->business->name;
                    }
                ),

            Column::make("location", "business_id")
            ->searchable()
            ->sortable()
            ->format(
                function ($value, $row) {
                    return $row->location->name;
                }
            ),
        ];
    }
}
