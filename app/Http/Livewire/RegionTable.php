<?php

namespace App\Http\Livewire;

use App\Models\DualControl;
use App\Models\Region;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RegionTable extends DataTableComponent
{
    use LivewireAlert, DualControlActivityTrait;

    protected $model = Region::class;
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
            Column::make('Location', 'location')
                ->sortable()
                ->searchable()
            ->format(function($value){
                return ucfirst($value);
            }),
            Column::make('Approval Status', 'is_approved')
                ->format(function ($value, $row) {
                    if ($value == 0) {
                        return <<< HTML
                            <span style="border-radius: 0 !important;" class="badge badge-warning p-2" >Not Approved</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<< HTML
                            <span style="border-radius: 0 !important;" class="badge badge-success p-2" >Approved</span>
                        HTML;
                    }
                    elseif ($value == 2) {
                        return <<< HTML
                            <span style="border-radius: 0 !important;" class="badge badge-danger p-2" >Rejected</span>
                        HTML;
                    }

                })->html(),
            Column::make('Action', 'id')
                ->format(function ($value) {
                    $edit = '';
                    $delete = '';
                    
                    if(Gate::allows('setting-region-edit')){
                        $edit =  <<< HTML
                        <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'region-edit-modal',$value)"><i class="bi bi-pencil-fill mr-1"></i> Edit</button>
                    HTML;
                    }
                    
                    if(Gate::allows('setting-region-delete')){
                    $delete = <<< HTML
                    <button class="btn btn-danger btn-sm" wire:click="delete($value)"><i class="bi bi-trash2-fill mr-1"></i> Delete</button>
                    HTML;
                    }
                    return $edit . $delete;
                })
                ->html(true),
        ];
    }


    public function delete($id)
    {
        if (!Gate::allows('setting-region-add')) {
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
        DB::beginTransaction();
        try {
            $data = (object) $value['data'];
            $region = Region::find($data->id);
            $this->triggerDualControl(get_class($region), $region->id, DualControl::DELETE, 'deleting region');
            DB::commit();
            $this->alert('success', DualControl::SUCCESS_MESSAGE,  ['timer'=>8000]);
            return;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            $this->alert('error', DualControl::ERROR_MESSAGE, ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
