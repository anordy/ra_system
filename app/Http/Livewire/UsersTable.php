<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class UsersTable extends DataTableComponent
{
    protected $model = User::class;
    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('First Name', 'fname')
                ->sortable()
                ->searchable(),
            Column::make('Last Name', 'lname')
                ->sortable()
                ->searchable(),
            Column::make('Phone', 'phone')
                ->sortable()
                ->searchable(),
            Column::make('Role', 'role_id')
                ->sortable()
                ->searchable(),
            Column::make('E-mail', 'email')
                ->sortable()
                ->searchable(),
            Column::make('Verified', 'email_verified_at')
                ->sortable(),
            Column::make('Action', 'id')
                ->format(fn ($value) => <<< HTML
                    <button class="btn btn-info btn-sm" onclick="Livewire.emit('openModal', 'staff::staff.dependant-edit-modal',[$value])"><i class="fa fa-edit"></i> </button>
                    <button class="btn btn-danger btn-sm" wire:click="delete($value)"><i class="fa fa-trash"></i> </button>
                HTML)
                ->html(true),
        ];
    }
}
