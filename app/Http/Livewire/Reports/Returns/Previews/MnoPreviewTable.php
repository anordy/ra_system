<?php

namespace App\Http\Livewire\Reports\Returns\Previews;

use App\Models\Returns\ExciseDuty\MnoReturn;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class MnoPreviewTable extends DataTableComponent
{
    use LivewireAlert;

    public $parameters;

    protected $listeners = ['refreshTable' => 'refreshTable', 'test'];

    public function mount($parameters)
    {
        // dd($parameters);
        $this->parameters = $parameters;
    }

    public function builder(): Builder
    {

        $dates = $this->parameters['dates'];
        if ($dates == []) {
            return MnoReturn::query()->orderBy('mno_returns.created_at', 'asc');
        }

        if ($dates['startDate'] == null || $dates['endDate'] == null) {
            return MnoReturn::query()->orderBy('mno_returns.created_at', 'asc');
        }

        return MnoReturn::query()->whereBetween('mno_returns.created_at', [$dates['startDate'], $dates['endDate']])->orderBy('mno_returns.created_at', 'asc');

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

        // $this->setAdditionalSelects(['mno_returns.name', 'mno_returns.phone', 'is_registered', 'taxpayer_id', 'mno_returns.created_at']);
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