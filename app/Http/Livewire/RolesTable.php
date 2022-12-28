<?php

namespace App\Http\Livewire;

use App\Models\DualControl;
use App\Models\Role;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RolesTable extends DataTableComponent
{
    use LivewireAlert, DualControlActivityTrait;

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
        $query = Role::query()->with('reportTo');
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
                    if (Gate::allows('setting-role-assign-permission')) {
                        return  <<< HTML
                            <button class="btn btn-success btn-sm" onclick="Livewire.emit('showModal', 'role-assign-permission-modal',$row->id)"><i class="fas fa-cog"></i>Configure Permission </button>
                        HTML;
                    }
                })->html(true),
            Column::make('Approval Status', 'is_approved')
                ->format(function ($value, $row) {
                    if ($value == 0) {
                        return <<< HTML
                            <span style="border-radius: 0 !important;" class="badge badge-warning p-2" >Not Approved</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<< HTML
                            <span style="border-radius: 0 !important;" class="badge badge-success p-2" >Approved</span>
                        HTML;
                    }
                    elseif ($value == 2) {
                        return <<< HTML
                            <span style="border-radius: 0 !important;" class="badge badge-danger p-2" >Rejected</span>
                        HTML;
                    }

                })->html(),
            Column::make('Action', 'id')
                ->format(function ($value) {
                    $edit = '';
                    $delete = '';

                    if (Gate::allows('setting-role-edit')) {
                        $edit =  <<< HTML
                                <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'role-edit-modal',$value)"><i class="fa fa-edit"></i> </button>
                            HTML;
                    }
                    if (Gate::allows('setting-role-delete')) {
                        $delete =  <<< HTML
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
        if (!Gate::allows('setting-role-delete')) {
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
            $role = Role::find($data->id);
            if (!$this->checkRelation($role, $role->id))
            {
                $this->alert('error', DualControl::RELATION_MESSAGE,  ['timer'=>4000]);
                return;
            }
            $this->triggerDualControl(get_class($role), $role->id, DualControl::DELETE, 'deleting role');
            $this->alert('success', DualControl::SUCCESS_MESSAGE,  ['timer'=>8000]);
            return;
        } catch (Exception $e) {
            report($e);
            $this->alert('error', DualControl::ERROR_MESSAGE, ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
