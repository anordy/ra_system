<?php

namespace App\Http\Livewire;

use App\Models\Notification;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class NotificationsTable extends DataTableComponent
{
    use LivewireAlert;

    public function builder(): Builder
{
    return Notification::query()
    ->where('notifiable_id',auth()->id())->latest()
    ->select();
}

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        $this->setTdAttributes(function(Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->isField('id')) {
              return [
                'style' => 'width: 20%;',
              ];
            }
        
            return [];
          });


    }

    protected $listeners = [
        'confirmed'
    ];

    public function columns(): array
    {
        
        return [
            Column::make('Time','created_at')
                ->sortable()
                ->searchable()
                ->format(
                    fn($value, $row, Column $column) =>$row->created_at->diffForHumans()
                ),
            Column::make('Message', 'data')
                ->sortable()
                ->searchable()
                ->format(
                    fn($value, $row, Column $column) =>$row['data']->messageLong
                ),
                Column::make('Action', 'id')
                ->format(fn ($value) => <<< HTML
                    <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> </button>
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
}
