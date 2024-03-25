<?php

namespace App\Http\Livewire;

use App\Models\ISIC2;
use App\Traits\WithSearch;
use Exception;
use Illuminate\Support\Facades\Gate;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ISIC2Table extends DataTableComponent
{
    use CustomAlert;

    protected $model = ISIC2::class;
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
            Column::make('Code', 'code')
                ->sortable()
                ->searchable(),
            Column::make('Description', 'description')
                ->sortable()
                ->searchable(),
            Column::make('Level 1 Code', 'isic1.code')
                ->sortable()
                ->searchable(),
            Column::make('Action', 'id')
                ->format(function ($value) {
                    $value = "'".encrypt($value)."'";
                    $edit = '';
                    $delete = '';

                    if (Gate::allows('setting-isic-level-two-edit')) {
                        $edit = <<< HTML
                        <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'i-s-i-c2-edit-modal',$value)"><i class="bi bi-pencil-square"></i> </button>
                    HTML;
                    }

                    if (Gate::allows('setting-isic-level-two-delete')) {
                        $delete = <<< HTML
                        <button class="btn btn-danger btn-sm" wire:click="delete($value)"><i class="bi bi-trash-fill"></i> </button>
                    HTML;
                    }

                    return $edit . $delete;
                })
                ->html(true),
        ];
    }


    public function delete($id)
    {
        $id = decrypt($id);
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
                'id' => $id
            ],

        ]);
    }

    public function confirmed($value)
    {
        try {
            $data = (object) $value['data'];
            $isic2 = ISIC2::find($data->id);
            if(is_null($isic2)){
                abort(404);
            }
            $isic2->delete();
            $this->flash('success', 'Record deleted successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            report($e);
            $this->customAlert('warning', 'Something whent wrong', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
