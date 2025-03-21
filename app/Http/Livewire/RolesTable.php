<?php

namespace App\Http\Livewire;

use App\Models\DualControl;
use App\Models\Role;
use App\Traits\DualControlActivityTrait;
use App\Traits\WithSearch;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RolesTable extends DataTableComponent
{
    use CustomAlert, DualControlActivityTrait;

    protected $model = Role::class;
    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    protected $listeners = [
        'confirmed'
    ];

    public function builder(): Builder
    {
        $query = Role::with('reportTo')->orderByDesc('id');
        return $query;
    }

    public function columns(): array
    {
        return [

            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Report', 'report_to')
                ->label(fn ($row) => $row->reportTo->name ?? '')
                ->sortable()
                ->searchable(),
            Column::make('Configuration')
                ->label(function ($row) {
                    $value = "'".encrypt($row->id)."'";
                    if (Gate::allows('setting-role-assign-permission')) {
                        return  <<< HTML
                            <button class="btn btn-success btn-sm" onclick="Livewire.emit('showModal', 'role-assign-permission-modal', $value)"><i class="fas fa-cog"></i>Configure Permission </button>
                        HTML;
                    }
                })->html(true),
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
                    }
                    elseif ($value == 2) {
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
                        if (Gate::allows('setting-role-edit') && approvalLevel(Auth::user()->level_id, 'Maker')) {
                            $edit =  <<< HTML
                                    <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'role-edit-modal',$value)"><i class="bi bi-pencil-square"></i> </button>
                                HTML;
                        }
                        if (Gate::allows('setting-role-delete') && approvalLevel(Auth::user()->level_id, 'Maker')) {
                            $delete =  <<< HTML
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
        if (!Gate::allows('setting-role-delete')) {
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
            $data = (object) $value['data'];
            $role = Role::findOrFail($data->id);
            if ($role->is_approved == DualControl::NOT_APPROVED) {
                $this->customAlert('error', 'The updated module has not been approved already');
                return;
            }
            if (!$this->checkRelation($role, $role->id))
            {
                $this->customAlert('error', DualControl::RELATION_MESSAGE,  ['timer'=>4000]);
                return;
            }
            $this->triggerDualControl(get_class($role), $role->id, DualControl::DELETE, 'deleting role');
            $this->customAlert('success', DualControl::SUCCESS_MESSAGE,  ['timer'=>8000]);
            $this->flash('success', DualControl::SUCCESS_MESSAGE, [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            report($e);
            $this->customAlert('error', DualControl::ERROR_MESSAGE, ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
