<?php

namespace App\Http\Livewire;

use App\Models\DualControl;
use App\Models\Street;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class StreetTable extends DataTableComponent
{
    use CustomAlert, DualControlActivityTrait;

    protected $model = Street::class;

//    public function builder() : Builder{
//        return Street::query()->orderBy('created_at', 'DESC');
//    }

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
            Column::make('Ward Name', 'ward.name')
                ->sortable()
                ->searchable(),
            Column::make('District', 'ward.district.name')
                ->sortable()
                ->searchable(),
            Column::make('Region', 'ward.district.region.name')
                ->sortable()
                ->searchable(),
            Column::make('Approval Status', 'is_approved')
                ->format(function ($value, $row) {
                    if ($value == 0) {
                        return <<<HTML
                            <span style="border-radius: 0 !important;" class="badge badge-warning p-2" >Not Approved</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<<HTML
                            <span style="border-radius: 0 !important;" class="badge badge-success p-2" >Approved</span>
                        HTML;
                    } elseif ($value == 2) {
                        return <<<HTML
                            <span style="border-radius: 0 !important;" class="badge badge-danger p-2" >Rejected</span>
                        HTML;
                    }
                })
                ->html(),
            Column::make('Edit Status', 'is_updated')
                ->format(function ($value) {
                    if ($value == 0) {
                        return <<<HTML
                            <span style="border-radius: 0 !important;" class="badge badge-warning p-2" >Not Updated</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<<HTML
                            <span style="border-radius: 0 !important;" class="badge badge-success p-2" >Updated</span>
                        HTML;
                    }
                })
                ->html(),
            Column::make('Action', 'id')
                ->format(function ($value, $row) {
                    $edit = '';
                    $delete = '';
                    $value = "'".encrypt($value)."'";
                    if ($row->is_approved == 1) {
                        if (Gate::allows('setting-street-edit') && approvalLevel(Auth::user()->level_id, 'Maker')) {
                            $edit = <<<HTML
                                <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'street-edit-modal',$value)"><i class="fa fa-edit"></i> </button>
                            HTML;
                        }
                        if (Gate::allows('setting-street-delete') && approvalLevel(Auth::user()->level_id, 'Maker')) {
                            $delete = <<<HTML
                                <button class="btn btn-danger btn-sm" wire:click="delete($value)"><i class="fa fa-trash"></i> </button>
                            HTML;
                        }
                    }
                    return $edit . ' ' . $delete;
                })
                ->html(true),
        ];
    }

    public function delete($id)
    {
        if (!Gate::allows('setting-ward-delete')) {
            abort(403);
        }
        $id = decrypt($id);
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
            $street = Street::find($data->id);
            if ($street->is_approved == DualControl::NOT_APPROVED) {
                $this->customAlert('error', DualControl::UPDATE_ERROR_MESSAGE);
                return;
            }
            $this->triggerDualControl(get_class($street), $street->id, DualControl::DELETE, 'deleting ward');
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
