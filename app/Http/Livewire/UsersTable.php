<?php

namespace App\Http\Livewire;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\Audit;
use App\Models\DualControl;
use App\Models\Role;
use App\Models\User;
use App\Traits\AuditTrait;
use App\Traits\DualControlActivityTrait;
use App\Traits\WithSearch;
use Exception;
use id;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class UsersTable extends DataTableComponent
{
    use CustomAlert, AuditTrait, ThrottlesLogins, DualControlActivityTrait;

    protected $model = User::class;
    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['status', 'is_first_login']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function builder(): Builder
    {
        return User::query()->orderByDesc('id');
    }

    protected $listeners = [
        'confirmed',
        'toggleStatus',
        'sendCredential'
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
                        $value = "'".encrypt($row->id)."'";
                        return  <<< HTML
                            <button class="btn btn-success btn-sm" onclick="Livewire.emit('showModal', 'assign-approval-level-add-modal', $value)"><i class="fas fa-cog mr-2"></i>Add Level</button>
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
                    } elseif ($value == 2) {
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
                    $mail = '';
                    $value = "'".encrypt($value)."'";

                    if ($row->is_approved == 1) {
                        if (Gate::allows('setting-user-edit') && approvalLevel(Auth::user()->level_id, 'Maker') ) {
                            $edit = <<< HTML
                                        <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'user-edit-modal', $value)"><i class="fa fa-edit"></i> </button>
                                    HTML;
                        }
                    }
                    if (Gate::allows('setting-user-change-password')) {
                        $changePwd = <<< HTML
                                    <button class="btn btn-warning btn-sm" onclick="Livewire.emit('showModal', 'user-change-password-modal',$value)"><i class="fa fa-key"></i> </button>
                                HTML;
                    }

                    if ($row->is_approved == 1) {
                        if (Gate::allows('setting-user-delete') && $value != auth()->user()->id && approvalLevel(Auth::user()->level_id, 'Maker')) {
                            $delete = <<< HTML
                                        <button class="btn btn-danger btn-sm" wire:click="delete($value)"><i class="fa fa-trash"></i> </button>
                                    HTML;
                        }
                    }

                    if ($row->is_first_login == 1) {
                        $mail = <<< HTML
                            <button class="btn btn-secondary btn-sm" wire:click="resendCredential($value)"><i class="fa fa-envelope"></i> </button>
                        HTML;
                    }


                    return $edit . $changePwd . $delete . $mail;
                })
                ->html(true),

            Column::make('Role Action', 'role.id')
                ->format(function ($value, $row) {
                    $value = "'".encrypt($row->id)."'";
                    if ($row->is_approved == 1) {
                        if (Gate::allows('setting-user-change-role')) {
                            return <<< HTML
                                        <button class="btn btn-success btn-sm" onclick="Livewire.emit('showModal', 'user-role-edit-modal', $value)"><i class="fa fa-user-tag"></i> </button>
                                    HTML;
                        }
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

    public function resendCredential($id)
    {
        $this->customAlert('warning', 'Are you sure you want to re-send new user credentials ?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Yes',
            'onConfirmed' => 'sendCredential',
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

        $this->customAlert('warning', 'Are you sure you want to change user status ?', [
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
        DB::beginTransaction();
        try {
            $data = (object) $value['data'];
            $user = User::find($data->id);
            if(is_null($user)){
                abort(404);
            }
            if ($user->is_approved == DualControl::NOT_APPROVED) {
                $this->customAlert('error', 'The updated module has not been approved already');
                return;
            }
            if ($user->status == 1) {
                $this->triggerAudit(User::class, Audit::DEACTIVATED, 'deactivate_user', $user->id, ['status' => 1], ['status' => 0]);
                $this->triggerDualControl(get_class($user), $user->id, DualControl::DEACTIVATE, 'deactivating user', json_encode(['status' => 1]), json_encode(['status' => 0]));
            } else {
                $this->triggerAudit(User::class, Audit::ACTIVATED, 'activate_user', $user->id, ['status' => 0], ['status' => 1]);
                $this->triggerDualControl(get_class($user), $user->id, DualControl::ACTIVATE, 'activating user', json_encode(['status' => 0]), json_encode(['status' => 1, 'auth_attempt' => 0]));
            }
            DB::commit();
            $this->customAlert('success', DualControl::SUCCESS_MESSAGE,  ['timer' => 8000]);
            return;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            $this->customAlert('error', DualControl::ERROR_MESSAGE, ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }

    public function sendCredential($value)
    {
        $data = (object) $value['data'];
        $user = User::find(decrypt($data->id));
        if(is_null($user)){
            abort(404);
        }
        event(new SendSms('user_add', $user->id));
        event(new SendMail('user_add', $user->id));

        $this->flash('success', 'Credentials re-send successfully', [], redirect()->back()->getTargetUrl());

    }

    public function confirmed($value)
    {
        try {
            $data = (object) $value['data'];
            $user = User::find($data->id);
            if(is_null($user)){
                abort(404);
            }
            if ($user->is_approved == DualControl::NOT_APPROVED) {
                $this->customAlert('error', 'The updated module has not been approved already');
                return;
            }
            $this->triggerDualControl(get_class($user), $user->id, DualControl::DELETE, 'deleting user');
            $this->customAlert('success', DualControl::SUCCESS_MESSAGE,  ['timer' => 8000]);
            return;
        } catch (Exception $e) {
            report($e);
            $this->customAlert('error', DualControl::ERROR_MESSAGE, ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
