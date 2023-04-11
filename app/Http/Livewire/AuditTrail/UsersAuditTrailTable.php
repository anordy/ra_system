<?php

namespace App\Http\Livewire\AuditTrail;

use App\Models\User;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class UsersAuditTrailTable extends DataTableComponent
{
    use CustomAlert;

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
            Column::make('E-mail', 'email')
                ->sortable()
                ->searchable(),
            Column::make('Role', 'role.name')
                ->sortable()
                ->searchable(),
            Column::make('Action', 'id')
                ->view('audit-trail.includes.actions')
        ];
    }

}
