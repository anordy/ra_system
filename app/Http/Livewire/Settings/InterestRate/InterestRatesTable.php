<?php

namespace App\Http\Livewire\Settings\InterestRate;

use App\Models\DualControl;
use App\Traits\DualControlActivityTrait;
use App\Traits\WithSearch;
use Exception;
use App\Models\InterestRate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class InterestRatesTable extends DataTableComponent
{
    use CustomAlert, DualControlActivityTrait;

    public function builder(): Builder
    {
        return InterestRate::query()
            ->orderBy('year', 'Desc');
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
            Column::make('Year', 'year')
                ->sortable()
                ->searchable(),
            Column::make('Rate', 'rate')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return number_format($value, 4);
                }),
            Column::make('Approval Status', 'is_approved')
                ->format(function ($value, $row) {
                    if ($value == 0) {
                        return <<< HTML
                            <span class="badge badge-warning p-2 rounded-0" >Not Approved</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<< HTML
                            <span class="badge badge-success p-2 rounded-0" >Approved</span>
                        HTML;
                    } elseif ($value == 2) {
                        return <<< HTML
                            <span class="badge badge-danger p-2 rounded-0" >Rejected</span>
                        HTML;
                    }

                })->html(),
            Column::make('Edit Status', 'is_updated')
                ->format(function ($value, $row) {
                    if ($value == 0) {
                        return <<<HTML
                            <span class="badge badge-warning p-2 rounded-0" >Not Updated</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<<HTML
                            <span class="badge badge-success p-2 rounded-0" >Updated</span>
                        HTML;
                    }
                })
                ->html(),
            Column::make('Action', 'id')
                ->format(function ($value, $row) {
                    $button = '';
                    $value = "'".encrypt($value)."'";
                    if ($row->is_approved == 1) {
                        if (approvalLevel(Auth::user()->level_id, 'Maker')) {
                            $button = <<< HTML
                                    <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'settings.interest-rate.interest-rate-edit-modal',$value)"><i class="bi bi-pencil-square"></i> </button>
                                    <button class="btn btn-danger btn-sm" wire:click="delete($value)"><i class="bi bi-trash-fill"></i> </button>
                                HTML;
                        }
                    }

                    return $button;
                })->html(true),
        ];
    }


    public function delete($id)
    {
        $id = decrypt($id);
        if (!Gate::allows('setting-interest-rate-delete')) {
            abort(403);
        }
        $this->customAlert('warning', 'Are you sure you want to delete ?', [
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
            $data = (object)$value['data'];
            $rate = InterestRate::find($data->id);
            if(is_null($rate)){
                abort(404);
            }
            $this->triggerDualControl(get_class($rate), $rate->id, DualControl::DELETE, 'deleting interest rate');
            $this->customAlert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 8000]);
            return;
        } catch (Exception $e) {
            report($e);
            $this->customAlert('error', DualControl::ERROR_MESSAGE, ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
