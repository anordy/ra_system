<?php

namespace App\Http\Livewire;

use App\Models\Notification;
use Carbon\Carbon;
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
            ->where('notifiable_id', auth()->id())->latest()
            ->select();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
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
            Column::make('Time', 'created_at')
                ->sortable()
                ->searchable()
                ->format(
                    fn ($value, $row, Column $column) => $row->created_at->diffForHumans()
                ),
            Column::make('Message', 'data')
                ->sortable()
                ->searchable()
                ->format(
                    fn ($value, $row, Column $column) => $row['data']->messageLong
                ),
            Column::make('Status', 'read_at')
                ->format(function ($value, $row) {
                    if (isset($value)) {
                        return <<< HTML
                        <span class="badge badge-success">Read</span>
                    HTML;
                    } else {
                        return <<< HTML
                        <span class="badge badge-warning">Unread</span>
                    HTML;
                    }
                })
                ->html(true),
            Column::make('Action', 'id')
                ->format(
                    fn ($value) => <<< HTML
                <button class="btn btn-info btn-link btn-sm" title="" wire:click="read($value)"><i class="fa fa-eye"></i> </button>
                <button class="btn btn-danger btn-sm" wire:click="delete($value)"><i class="fa fa-trash"></i> </button>
            HTML
                )
                ->html(true),
        ];
    }

    public function read($id)
    {
        $notification = Notification::find($id);
        $notification->read_at = Carbon::now();
        $notification->save();
        return redirect()->to($notification['data']->href);
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

    public function confirmed($value)
    {
        try {
            $data = (object) $value['data'];
            Notification::find($data->id)->delete();
            $this->flash('success', 'Record deleted successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            report($e);
            $this->alert('warning', 'Something whent wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
