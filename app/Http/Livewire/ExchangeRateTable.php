<?php

namespace App\Http\Livewire;

use App\Models\DualControl;
use App\Models\ExchangeRate;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ExchangeRateTable extends DataTableComponent
{
    use LivewireAlert, DualControlActivityTrait;

    protected $model = ExchangeRate::class;
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

    protected $listeners = ['confirmed'];

    public function columns(): array
    {
        return [
            Column::make('Name', 'currency')
                ->sortable()
                ->searchable(),
            Column::make('Spot Buying', 'spot_buying')
                ->sortable()
                ->searchable(),
            Column::make('Spot Selling', 'spot_selling')
                ->sortable()
                ->searchable(),
            Column::make('Mean Rate', 'mean')
                ->sortable()
                ->searchable(),
            Column::make('Exchange Date', 'exchange_date')
                ->sortable()
                ->searchable(),
            Column::make('Action', 'id')
                ->format(
                    function ($value) {
                        $edit = '';
                        $delete = '';

                        if (Gate::allows('setting-exchange-rate-edit')) {
                            $edit = <<< HTML
                                <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'exchange-rate-edit-modal',$value)"><i class="fa fa-edit"></i> </button>
                            HTML;
                        }

                        if (Gate::allows('setting-exchange-rate-delete')) {
                            $delete = <<< HTML
                                <button class="btn btn-danger btn-sm" wire:click="delete($value)"><i class="fa fa-trash"></i> </button>
                            HTML;
                        }

                        return $edit . $delete;
                    })
                        
                ->html(true),
        ];
    }

    public function delete($id)
    {
        if (!Gate::allows('setting-exchange-rate-delete')) {
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
                'id' => $id,
            ],
        ]);
    }

    public function confirmed($value)
    {
        DB::beginTransaction();
        try {
            $data = (object) $value['data'];
            $country = ExchangeRate::find($data->id);
            $this->triggerDualControl(get_class($country), $country->id, DualControl::DELETE, 'deleting exchange rate');
            DB::commit();
            $this->alert('success', DualControl::SUCCESS_MESSAGE,  ['timer'=>8000]);
            return;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            $this->alert('error', DualControl::ERROR_MESSAGE, ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
