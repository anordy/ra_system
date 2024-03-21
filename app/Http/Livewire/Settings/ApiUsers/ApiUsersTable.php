<?php

namespace App\Http\Livewire\Settings\ApiUsers;

use App\Models\ApiUser;
use App\Models\Audit;
use App\Models\DualControl;
use App\Traits\AuditTrait;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ApiUsersTable extends DataTableComponent
{
    use CustomAlert, AuditTrait, DualControlActivityTrait;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['status']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered',
        ]);
    }

    public function builder(): Builder
    {
        return ApiUser::orderByDesc('id');
    }

    protected $listeners = [
        'toggleStatus',
    ];

    public function columns(): array
    {
        return [

            Column::make('App Name', 'app_name')
                ->sortable()
                ->searchable(),
            Column::make('Username', 'username')
                ->sortable()
                ->searchable(),
            Column::make('URL', 'app_url')
                ->sortable()
                ->searchable(),
            Column::make('Status', 'status')
                ->label(function ($row) {
                     if ($row->status == 1 && Gate::allows('setting-user-change-status')) {
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
                    $edit = '';
                    $delete = '';
                    $value = "'".encrypt($value)."'";
                    if ($row->is_approved == 1) {
                        if (Gate::allows('setting-role-edit') && approvalLevel(Auth::user()->level_id, 'Maker')) {
                            $edit =  <<< HTML
                                    <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'settings.api-users.edit-modal',$value)"><i class="fa fa-edit"></i> </button>
                                HTML;
                        }
                    }

                    return $edit . $delete;
                })
                ->html(true),
        ];
    }

    public function activate($id, $status)
    {
        if (!Gate::allows('setting-user-change-status')) {
            abort(403);
        }

        $this->customAlert('warning', 'Are you sure you want to change user for API status ?', [
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
            $user = ApiUser::find($data->id);
            if(is_null($user)){
                abort(404);
            }
            if ($user->is_approved == DualControl::NOT_APPROVED) {
                $this->customAlert('error', 'The updated module has not been approved already');
                return;
            }
            if ($user->status == 1) {
                $this->triggerAudit(get_class($user), Audit::DEACTIVATED, 'deactivate_user '.$user->username, $user->id, ['status' => 1], ['status' => 0]);
                $this->triggerDualControl(get_class($user), $user->id, DualControl::DEACTIVATE, 'deactivating user', json_encode(['status' => 1]), json_encode(['status' => 0]));
            } else {
                $this->triggerAudit(get_class($user), Audit::ACTIVATED, 'activate_user '.$user->username, $user->id, ['status' => 0], ['status' => 1]);
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
}
