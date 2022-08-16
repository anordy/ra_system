<?php

namespace App\Http\Livewire\Reports\Returns;

use App\Models\LandLease;
use App\Models\Returns\ExciseDuty\MnoReturn;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PreviewTable extends Component
{
    use LivewireAlert;

    public $parameters;

    protected $listeners = ['refreshTable' => 'refreshTable', 'test'];

    public function mount($parameters)
    {
        dd($parameters);
    }

    public function builder(): Builder
    {

        return MnoReturn::query();

        // if ($dates == []) {
        //     return LandLease::query()->orderBy('land_leases.created_at', 'asc');
        // }

        // if ($dates['startDate'] == null || $dates['endDate'] == null) {
        //     return LandLease::query()->orderBy('land_leases.created_at', 'asc');
        // }

        // return LandLease::query()->whereBetween('land_leases.created_at', [$dates['startDate'], $dates['endDate']])->orderBy('land_leases.created_at', 'asc');

    }

    public function refreshTable($dates)
    {
        $this->dates = $dates;
        $this->builder();
    }
    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        // $this->setAdditionalSelects(['land_leases.name', 'land_leases.phone', 'is_registered', 'taxpayer_id', 'land_leases.created_at']);
    }

    public function columns(): array
    {
        return [
            Column::make("Register Date", "created_at")
                ->format(function ($value, $row) {
                    return date('d/m/Y', strtotime($value));
                })
                ->searchable()
                ->sortable(),
        ];
    }
}