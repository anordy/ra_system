<?php

namespace App\Http\Livewire;

use App\Models\Notification;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class NotificationsTable extends DataTableComponent
{
    use LivewireAlert;

    public function mount()
    {
        $user = auth()->user();
        Notification::where('notifiable_type', get_class($user))
            ->where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => Carbon::now()]);
    }

    public function builder(): Builder
    {
        return Notification::query()
            ->where('notifiable_type', get_class(auth()->user()))
            ->where('notifiable_id', auth()->id())
            ->latest()
            ->select();
    }

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

    public function columns(): array
    {

        return [
            Column::make('Time', 'created_at')
                ->sortable()
                ->searchable()
                ->format(
                    fn ($value, $row) => $row->created_at->diffForHumans()
                ),
            Column::make('Subject', 'data')
                ->format(
                    fn ($value, $row) => $row['data']->subject
                ),
            Column::make('Message', 'type')
                ->format(
                    fn ($value, $row) => $row['data']->message
                ),
            Column::make('Action', 'id')
                ->label(
                    function ($row) {
                        $id = "'{$row->id}'";
                        return <<< HTML
                            <button class="btn btn-info btn-sm" title="View" wire:click="read($id)"><i class="fa fa-eye"></i></button>
                            <button class="btn btn-danger btn-sm" title="Delete" wire:click="delete($id)"><i class="fa fa-trash"></i> </button>
                        HTML;
                    }
                )
                ->html(true),
        ];
    }

    public function read($id)
    {
        try {
            $notification = Notification::find($id);
            if($notification == null){
                return redirect()->back();
            }
            if ($notification['data']->href != null) {
                if ($notification['data']->hrefParameters != null) {
                    // dd($notification['data']->href, $notification['data']->hrefParameters);
                    return redirect()->route($notification['data']->href, encrypt($notification['data']->hrefParameters));
                }
                return redirect()->route($notification['data']->href);
            }
            return redirect()->back();
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
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
