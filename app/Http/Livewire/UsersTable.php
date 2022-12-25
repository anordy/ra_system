<?php

namespace App\Http\Livewire;

use App\Models\Audit;
use App\Models\User;
use App\Traits\AuditTrait;
use Exception;
use id;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class UsersTable extends DataTableComponent
{
    use LivewireAlert, AuditTrait,ThrottlesLogins;

    protected $model = User::class;
    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['status']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    protected $listeners = [
        'confirmed',
        'toggleStatus',
    ];

    public function columns(): array
    {
        return [
            Column::make('First Name', 'fname')
                ->sortable()
                ->searchable(),
            Column::make('Last Name', 'lname')
                ->sortable()
                ->searchable(),
            Column::make('Gender', 'gender')
                ->format(function ($value) {
                    if ($value == 'M') {
                        return 'Male';
                    } elseif ($value == 'F') {
                        return 'Female';
                    }
                })
                ->sortable()
                ->searchable(),
            Column::make('Phone', 'phone')
                ->sortable()
                ->searchable(),
            Column::make('E-mail', 'email')
                ->sortable()
                ->searchable(),
            Column::make('Role', 'role.name')
                ->sortable()
                ->searchable(),
            Column::make('Approval Level', 'level.name')
                ->sortable()
                ->searchable(),
            Column::make('Configuration', 'created_at')
                ->format(function ($value, $row) {
                    if (Gate::allows('setting-role-assign-permission')) {
                        return  <<< HTML
                            <button class="btn btn-success btn-sm" onclick="Livewire.emit('showModal', 'assign-approval-level-add-modal',$row->id)"><i class="fas fa-cog mr-2"></i>Add Level</button>
                        HTML;
                    }
                })->html(true),
            Column::make('Status', 'status')
                ->label(function ($row) {
                    if ($row->id == auth()->user()->id) {
                        return "";
                    } else if ($row->status == 1 && Gate::allows('setting-user-change-status')) {
                        return <<< HTML
                            <button class="btn btn-info btn-sm" wire:click="activate($row->id, $row->status)"><i class="fa fa-lock-open"></i> </button>
                        HTML;
                    } else if ($row->status != 1 && Gate::allows('setting-user-change-status')) {
                        return  <<< HTML
                            <button class="btn btn-danger btn-sm" wire:click="activate($row->id, $row->status)"><i class="fa fa-lock"></i> </button>
                        HTML;
                    }
                })
                ->html(true),
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
                ->format(function ($value, $row) {
                    $edit = '';
                    $changePwd = '';
                    $delete = '';

                    if (Gate::allows('setting-user-edit')) {
                        $edit = <<< HTML
                                    <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'user-edit-modal',$value)"><i class="fa fa-edit"></i> </button>
                                HTML;
                    }
                    if (Gate::allows('setting-user-change-password')) {
                        $changePwd = <<< HTML
                                    <button class="btn btn-warning btn-sm" onclick="Livewire.emit('showModal', 'user-change-password-modal',$value)"><i class="fa fa-key"></i> </button>
                                HTML;
                    }
                    if (Gate::allows('setting-user-delete') && $value != auth()->user()->id) {
                        $delete = <<< HTML
                                    <button class="btn btn-danger btn-sm" wire:click="delete($value)"><i class="fa fa-trash"></i> </button>
                                HTML;
                    }

                    return $edit . $changePwd . $delete;
                })
                ->html(true),

            Column::make('Role Action', 'role.id')
                ->format(function ($value, $row) {
                    if (Gate::allows('setting-user-change-role')) {
                        return <<< HTML
                                    <button class="btn btn-success btn-sm" onclick="Livewire.emit('showModal', 'user-role-edit-modal',$value)"><i class="fa fa-user-tag"></i> </button>
                                HTML;
                    }
                })
                ->html(true),
        ];
    }

    public function delete($id)
    {
        if (!Gate::allows('setting-user-delete')) {
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

    public function activate($id, $status)
    {
        if (!Gate::allows('setting-user-change-status')) {
            abort(403);
        }

        $this->alert('warning', 'Are you sure you want to change user status ?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => $status == 0 ? 'Activate' : 'Deactivate',
            'onConfirmed' => 'toggleStatus',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $id,
            ],

        ]);
    }

    public function toggleStatus($value)
    {
        try {
            $data = (object) $value['data'];
            $user = User::find($data->id);
            if ($user->status == 1) {
                $user->status = 0;
                $user->save();
                $this->triggerAudit(User::class, Audit::DEACTIVATED, 'deactivate_user', $user->id, ['status' => 1], ['status' => 0]);
            } else {
                $user->status = 1;
                $user->auth_attempt = 0;
                $user->save();
                $this->triggerAudit(User::class, Audit::ACTIVATED, 'activate_user', $user->id, ['status' => 0], ['status' => 1]);
            }
        } catch (Exception $e) {
            report($e);
            $this->alert('warning', 'Something whent wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }

    public function confirmed($value)
    {
        try {
            $data = (object) $value['data'];
            User::find($data->id)->delete();
            $this->flash('success', 'Record deleted successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            report($e);
            $this->alert('warning', 'Something whent wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
