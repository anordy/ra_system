<?php

namespace App\Http\Livewire\Settings\ViableTaxType;

use App\Enum\CustomMessage;
use App\Enum\GeneralConstant;
use App\Models\TaxType;
use App\Traits\CustomAlert;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxTypesTable extends DataTableComponent
{
    use CustomAlert;

    public $isMaker = false;

    public function mount() {
        $this->isMaker = approvalLevel(Auth::user()->level_id, GeneralConstant::MAKER);
    }

    public function builder(): Builder
    {
        return TaxType::main();
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
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Approval Status', 'is_approved')
                ->format(function ($value, $row) {
                    if ($value == 0) {
                        return <<< HTML
                            <span  class="badge badge-warning p-2 rounded-0" >Not Approved</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<< HTML
                            <span  class="badge badge-success p-2 rounded-0" >Approved</span>
                        HTML;
                    }
                    elseif ($value == 2) {
                        return <<< HTML
                            <span  class="badge badge-danger p-2 rounded-0" >Rejected</span>
                        HTML;
                    }

                })->html(),
            Column::make('Edit Status', 'is_updated')
                ->format(function ($value, $row) {
                    if ($value == 0) {
                        return <<<HTML
                            <span  class="badge badge-warning p-2 rounded-0" >Not Updated</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<<HTML
                            <span  class="badge badge-success p-2 rounded-0" >Updated</span>
                        HTML;
                    }
                })
                ->html(),
            Column::make('Action', 'id')
                ->format(function ($value, $row) {
                    $edit = '';
                    $delete = '';
                    $value = "'".encrypt($value)."'";

                    if ($row->is_approved == 1) {
                        if (Gate::allows('setting-tax-type-edit') && !$this->isMaker) {
                            $edit =  <<< HTML
                                    <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'settings.viable-tax-type.add-viable-tax-type-modal',$value)"><i class="bi bi-pencil-square"></i> </button>
                                HTML;
                        }

                        if (Gate::allows('setting-tax-type-delete') && $this->isMaker) {
                            $delete =  <<< HTML
                                    <button class="btn btn-danger btn-sm" wire:click="delete($value)"><i class="bi bi-trash-fill"></i> </button>
                                HTML;
                        }
                    }
                    return $edit . $delete;
                })
                ->html(true),
        ];
    }


    public function delete($id)
    {
        if (!Gate::allows('setting-tax-type-delete')) {
            abort(403);
        }
        $id = decrypt($id);
        $this->customAlert('warning', CustomMessage::ARE_YOU_SURE, [
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
            $deleted = TaxType::findOrFail($data->id)->delete();
            if (!$deleted) throw new Exception('Failed to delete tax type');
            $this->flash('success', 'Record deleted successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error('TAX-TYPE-TABLE-CONFIRMED', [$e]);
            $this->customAlert('warning', 'Something went wrong', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
