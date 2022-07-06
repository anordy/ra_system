<?php

namespace App\Http\Livewire;

use Exception;
use Carbon\Carbon;
use App\Models\Audit;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class AuditLogTable extends DataTableComponent
{
    use LivewireAlert;

    public function builder(): Builder {
        return Audit::query()->join('users', 'users.id', '=', 'audits.user_id')->orderBy('created_at', 'DESC');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['auditable_type', 'tags', 'new_values', 'auditable_id']);
        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->isField('id')) {
                return [
                    'style' => 'width: 20%;',
                ];
            }

            return [];
        });
    }



    public function columns(): array
    {
        return [
            Column::make('Log', 'user_id')
                ->sortable()
                ->searchable()
                ->format(fn($value, $row, Column $column) => $row->user->fname . ' ' . $row->user->lname . ' ' .$row->event . ' '. preg_split('[\\\]', $row->auditable_type)[2] ." ".$row->auditable_type::find($row->auditable_id)->name ?? ''),
            Column::make('Action', 'event')
                ->sortable()
                ->searchable(),
            Column::make('IP Address', 'ip_address')
                ->sortable()
                ->searchable(),
            Column::make('Time', 'created_at')
                ->sortable()
                ->searchable()
                ->format(fn($value, $row, Column $column) => Carbon::create($row->created_at)->diffForHumans()),
            Column::make('Action', 'id')
                ->format(fn ($value) => <<< HTML
                    <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'audit-view-modal',$value)"><i class="fa fa-eye"></i> </button>
                HTML)
                ->html(true)
        ];
    }


}
