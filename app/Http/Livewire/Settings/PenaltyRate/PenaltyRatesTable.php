<?php

namespace App\Http\Livewire\Settings\PenaltyRate;

use App\Models\DualControl;
use App\Models\InterestRate;
use App\Traits\DualControlActivityTrait;
use Exception;
use App\Models\PenaltyRate;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class PenaltyRatesTable extends DataTableComponent
{
    use LivewireAlert, DualControlActivityTrait;

    public function builder(): Builder
    {
        return  PenaltyRate::query()
            ->orderBy('financial_year_id', 'Desc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->isField('id')) {
                return [
                    'style' => 'width: 20%;',
                ];
            }

            return [];
        });
    }

    protected $listeners = [
        'confirmed'
    ];

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Code', 'code')
                ->sortable()
                ->searchable(),
            Column::make('Rate', 'rate')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                }),
            Column::make('Financial Year', 'year.code')
                ->sortable()
                ->searchable(),
            Column::make('Action', 'id')
                ->view('settings.penalty-rate.includes.actions'),
        ];
    }


    public function delete($id)
    {
        if (!Gate::allows('setting-penalty-rate-delete')) {
            abort(403);
        }
        $this->alert('warning', 'Are you sure you want to delete ?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Delete',
            'onConfirmed' => 'confirmed',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $id
            ],

        ]);
    }

    public function confirmed($value)
    {
        try {
            $data = (object) $value['data'];
            $rate = PenaltyRate::find(decrypt($data->id));
            $this->triggerDualControl(get_class($rate), $rate->id, DualControl::DELETE, 'deleting penalty rate');
            $this->alert('success', DualControl::SUCCESS_MESSAGE,  ['timer'=>8000]);
            return;
        } catch (Exception $e) {
            report($e);
            $this->alert('error', DualControl::ERROR_MESSAGE, ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
