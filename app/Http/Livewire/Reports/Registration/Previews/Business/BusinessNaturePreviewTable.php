<?php

namespace App\Http\Livewire\Reports\Registration\Previews\Business;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\BusinessStatus;
use App\Traits\RegistrationReportTrait;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class BusinessNaturePreviewTable extends DataTableComponent
{
    use LivewireAlert,RegistrationReportTrait;

    public $isicId;
    public $level;

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setTableWrapperAttributes([
            'default' => true,
            'class'   => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['business_locations.business_id']);
    }

    public function mount($isicId,$level){
        $this->isic1Id = $isicId;
        $this->level = $level;
        // dd($isicId,$level);
    }

    public function builder(): Builder
    {
        if($this->level == 1){
            return $this->businessByNatureIsic1Query($this->isicId);
        }
        elseif($this->level == 2){
            return $this->businessByNatureIsic2Query($this->isicId);
        }
        elseif($this->level == 3){
            return $this->businessByNatureIsic3Query($this->isicId);
        }
        elseif($this->level == 4){
            return $this->businessByNatureIsic4Query($this->isicId);
        }
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
