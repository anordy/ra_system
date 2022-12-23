<?php

namespace App\Http\Livewire;

use App\Models\Role;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RolesTable extends DataTableComponent
{
    use LivewireAlert;

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
            Column::make('Configuration', 'created_at')
                ->format(function ($value, $row) {
                    if (Gate::allows('setting-role-assign-permission')) {
                        return  <<< HTML
                            <button class="btn btn-success btn-sm" onclick="Livewire.emit('showModal', 'role-assign-permission-modal',$row->id)"><i class="fas fa-cog"></i>Permission </button>
                            <button class="btn btn-success btn-sm" onclick="Livewire.emit('showModal', 'role-assign-approval-level-add-modal',$row->id)"><i class="fas fa-cog mr-2"></i>Approval Level</button>

                        HTML;
                    }
                })->html(true),
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
                                <button class="btn btn-danger btn-sm" onclick="Livewire.emit('showModal', 'role-delete-modal',$value)"><i class="fa fa-trash"></i> </button>
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
            Role::find($data->id)->delete();
            $this->flash('success', 'Record deleted successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            report($e);
            $this->alert('warning', 'Something whent wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
