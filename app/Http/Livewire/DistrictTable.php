<?php

namespace App\Http\Livewire;

use App\Models\District;
use App\Models\DualControl;
use App\Traits\DualControlActivityTrait;
use App\Traits\WithSearch;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class DistrictTable extends DataTableComponent
{
    use CustomAlert, DualControlActivityTrait;

    protected $model = District::class;
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
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Region', 'region.name')
                ->sortable()
                ->searchable(),
            Column::make('Approval Status', 'is_approved')
                ->format(function ($value, $row) {
                    if ($value == 0) {
                        return <<<HTML
                            <span class="badge badge-warning p-2 rounded-0">Not Approved</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<<HTML
                            <span class="badge badge-success p-2 rounded-0" >Approved</span>
                        HTML;
                    } elseif ($value == 2) {
                        return <<<HTML
                            <span class="badge badge-danger p-2 rounded-0" >Rejected</span>
                        HTML;
                    }
                })
                ->html(),
            Column::make('Edit Status', 'is_updated')
                ->format(function ($value) {
                    if ($value == 0) {
                        return <<<HTML
                            <span class="badge badge-warning p-2 rounded-0" >Not Updated</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<<HTML
                            <span class="badge badge-success p-2 rounded-0" >Updated</span>
                        HTML;
                    } elseif ($value == 2) {
                        return <<<HTML
                            <span class="badge badge-success p-2 rounded-0" >Rejected</span>
                        HTML;
                    }
                })
                ->html(),
            Column::make('Action', 'id')
                ->format(function ($value, $row) {
                    $value = "'".encrypt($value)."'";
                    $edit = '';
                    $delete = '';

                    if ($row->is_approved == 1) {
                        if (Gate::allows('setting-district-edit') && approvalLevel(Auth::user()->level_id, 'Maker')) {
                            $edit = <<<HTML
                                <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'district-edit-modal',$value)"><i class="bi bi-pencil-square"></i> </button>
                            HTML;
                        }

                        if (Gate::allows('setting-district-delete') && approvalLevel(Auth::user()->level_id, 'Maker')) {
                            $delete = <<<HTML
                                <button class="btn btn-danger btn-sm" wire:click="delete($value)"><i class="bi bi-trash-fill"></i> </button>
                            HTML;
                        }
                    }

                    return $edit . $delete;
                })
                ->html(true),
        ];
    }

    public function delete($id)
    {
        $id = decrypt($id);
        if (!Gate::allows('setting-district-delete')) {
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
                'id' => $id,
            ],
        ]);
    }

    public function confirmed($value)
    {
        DB::beginTransaction();
        try {
            $data = (object) $value['data'];
            $district = District::find($data->id);
            if(is_null($district)){
                abort(404);
            }
            if ($district->is_approved == DualControl::NOT_APPROVED) {
                $this->customAlert('error', DualControl::UPDATE_ERROR_MESSAGE);
                return;
            }
            $this->triggerDualControl(get_class($district), $district->id, DualControl::DELETE, 'deleting district');
            DB::commit();
            $this->customAlert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 8000]);
            return;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            $this->customAlert('error', DualControl::ERROR_MESSAGE, ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
