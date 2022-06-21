<?php

namespace App\Http\Livewire;

use id;
use Exception;
use App\Models\User;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class UsersTable extends DataTableComponent
{
    use LivewireAlert;

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
        'toggleStatus'
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
                    if ($value == 'M') return 'Male';
                    elseif ($value == 'F') return 'Female';
                })
                ->sortable()
                ->searchable(),
            Column::make('Phone', 'phone')
                ->sortable()
                ->searchable(),
            Column::make('E-mail', 'email')
                ->sortable()
                ->searchable(),
            Column::make('Verified', 'email_verified_at')
                ->sortable(),
            Column::make('Role', 'role.name')
                ->sortable()
                ->searchable(),
            Column::make('Status', 'id')
                ->label(function ($row) {
                    if ($row->status == 1) {
                        return <<< HTML
                        <button class="btn btn-info btn-sm" wire:click="activate($row->id, $row->status)"><i class="fa fa-lock-open"></i> </button>
                    HTML;
                    } else {
                        return <<< HTML
                        <button class="btn btn-success btn-sm" wire:click="activate($row->id, $row->status)"><i class="fa fa-lock"></i> </button>
                    HTML;
                    }
                })
                ->html(true),
            Column::make('Action', 'id')
                ->format(fn ($value, $row) => <<< HTML
                    <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'user-edit-modal',$value)"><i class="fa fa-edit"></i> </button>
                    <button class="btn btn-danger btn-sm" wire:click="delete($value)"><i class="fa fa-trash"></i> </button>
                HTML)
                ->html(true),
        ];
    }


    public function delete($id)
    {
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

    public function activate($id, $status)
    {
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
                'id' => $id
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
            } else {
                $user->status = 1;
                $user->save();
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
