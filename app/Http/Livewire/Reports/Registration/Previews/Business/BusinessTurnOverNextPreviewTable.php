<?php

namespace App\Http\Livewire\Reports\Registration\Previews\Business;

use App\Models\TaxType;
use App\Traits\RegistrationReportTrait;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class BusinessTurnOverNextPreviewTable extends DataTableComponent
{
    use LivewireAlert,RegistrationReportTrait;

    public $from;
    public $to;

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setTableWrapperAttributes([
            'default' => true,
            'class'   => 'table-bordered table-sm',
        ]);
        // $this->setAdditionalSelects(['business_locations.business_id']);
    }

    public function mount($from,$to){
        $this->from = $from;
        $this->to = $to;
    }

    public function builder(): Builder
    {
        return $this->businessByTurnOverNextQuery($this->from,$this->to);
    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('TIN', 'tin')
            ->sortable()
            ->searchable(),

            Column::make('TIN', 'tin')
            ->sortable()
            ->searchable(),

            Column::make('Taxpayer', 'taxpayer_id')
            ->sortable()
            ->searchable()
            ->format(
                function ($value, $row) {
                    return $row->taxpayer->fullname;
                }
            ),
        ];
    }

}
