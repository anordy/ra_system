<?php

namespace App\Http\Livewire;

use App\Models\ISIC3;
use App\Traits\WithSearch;
use Exception;
use Illuminate\Support\Facades\Gate;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ISIC3Table extends DataTableComponent
{
    use CustomAlert;

    protected $model = ISIC3::class;
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
            Column::make('Level 2 Code', 'isic2.code')
                ->sortable()
                ->searchable(),
            Column::make('Action', 'id')
                ->format(function ($value) {
                    $value = "'".encrypt($value)."'";
                    $edit = '';
                    $delete = '';

                    if (Gate::allows('setting-isic-level-three-edit')) {
                        $edit = <<< HTML
                        <button class="btn btn-info btn-sm" id="showDataTableModal" data-modal-name="i-s-i-c3-edit-modal" data-modal-value="$value"><i class="bi bi-pencil-square"></i> </button>
                    HTML;
                    }

                    if (Gate::allows('setting-isic-level-three-delete')) {
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
        if (!Gate::allows('setting-isic-level-three-delete')) {
            abort(403);
        }

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
            $isic3 = ISIC3::find($data->id);
            if(is_null($isic3)){
                abort(404);
            }
            $this->flash('success', 'Record deleted successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            report($e);
            $this->customAlert('warning', 'Something whent wrong', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
