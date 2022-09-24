<?php

namespace App\Http\Livewire;

use App\Models\Bank;
use Exception;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class BanksTable extends DataTableComponent
{
    use LivewireAlert;

    protected $model = Bank::class;
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
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Action', 'id')
                ->format(function ($value) {
                    $edit = '';
                    $delete = '';

                    if (Gate::allows('setting-bank-edit')) {
                        $edit = <<< HTML
                            <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'bank-edit-modal',$value)"><i class="fa fa-edit"></i> </button>
                        HTML;
                    }

                    if (Gate::allows('setting-bank-delete')) {
                        $delete = <<< HTML
                            <button class="btn btn-danger btn-sm" wire:click="delete($value)"><i class="fa fa-trash"></i> </button>
                        HTML;
                    }

                    return $edit . $delete;
                })
                ->html(true),
        ];
    }


    public function delete($id)
    {
        if (!Gate::allows('setting-bank-delete')) {
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
                'id' => $id
            ],

        ]);
    }

    public function confirmed($value)
    {
        try {
            $data = (object) $value['data'];
            Bank::find($data->id)->delete();
            $this->flash('success', 'Record deleted successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            report($e);
            $this->alert('warning', 'Something whent wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
