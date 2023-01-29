<?php

namespace App\Http\Livewire;

use App\Models\Audit;
use App\Models\TransactionFee;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TransactionFeesTable extends DataTableComponent
{
    use LivewireAlert;

    protected $model = TransactionFee::class;

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
        'confirmed',
        'toggleApproval',
        'toggleReject',
    ];

    public function columns(): array
    {
        return [
            Column::make('Minimum Amount', 'minimum_amount')
                ->sortable()
                ->searchable(),
            Column::make('Maximum Amount', 'maximum_amount')
                ->sortable()
                ->searchable(),
            Column::make('Fee', 'fee')
                ->sortable()
                ->searchable(),
            Column::make('Created By', 'created_by')
                ->format(fn($id) => User::query()->find($id)->fullname())
                ->sortable(),
            Column::make('Created At', 'created_at')
                ->sortable()
                ->searchable(),
            Column::make("Status", "is_approved")
                ->sortable()->searchable()->format(function ($value) {
                if ($value == 0) {
                    return <<< HTML
                        <span class="text-danger" >Not Approved </span>
                    HTML;
                } elseif ($value == 1) {
                    return <<< HTML
                        <span class="text-success" >Approved </span>
                    HTML;
                } elseif ($value == 2) {
                    return <<< HTML
                        <span class="text-danger" >Rejected </span>
                    HTML;
                }
            })->html(true),
            Column::make('Action', 'id')
                ->format(function ($value, $row) {
                    $edit = '';
                    $delete = '';
                    $approve = '';
                    $reject = '';
                    if ($row->is_approved == 1) {
                        if (Gate::allows('setting-transaction-fees-edit') && approvalLevel(Auth::user()->level_id, 'maker')) {
                            $id = encrypt($value);
                            $edit = <<< HTML
                                <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'transaction-fees-edit-modal', $id)"><i class="fa fa-edit"></i> </button>
                            HTML;
                        }

                        if (Gate::allows('setting-transaction-fees-delete') && approvalLevel(Auth::user()->level_id, 'Maker')) {
                            $delete = <<< HTML
                                <button class="btn btn-danger btn-sm" wire:click="delete($value)"><i class="fa fa-trash"></i> </button>
                            HTML;
                        }
                    }
                    return $edit . $delete ;

                })
                ->html(true),
        ];
    }

    public function delete($id)
    {
        if (!Gate::allows('setting-transaction-fees-delete')) {
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
                'id' => $id,
            ],
        ]);
    }

    public function confirmed($value)
    {
        try {
            $data = (object) $value['data'];
            TransactionFee::find($data->id)->delete();
            $this->flash('success', 'Record deleted successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            report($e);
            $this->alert('warning', 'Something went wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }

       public function approve($id)
    {
        $this->alert('warning', 'Are you sure you want to approve this fee ?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Approve',
            'onConfirmed' => 'toggleApproval',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $id,
            ],

        ]);
    }

    public function toggleApproval($value)
    {
        DB::beginTransaction();
        try {
            $data = (object)$value['data'];
            $fee = TransactionFee::find($data->id);
            $fee->is_approved = 1;
            $fee->save();
            $this->triggerAudit(EgaFee::class, Audit::ACTIVATED, 'ega_fee', $fee->id, ['status' => 0], ['status' => 1]);
            DB::commit();
            $this->alert('success', 'Ega fee successfully approved');
            return redirect()->route('settings.fee-configurations.ega-fee');
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            $this->alert('warning', 'Something went wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }

    public function reject($id)
    {
        $this->alert('warning', 'Are you sure you want to reject this fee ?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Reject',
            'onConfirmed' => 'toggleReject',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $id,
            ],

        ]);
    }

    public function toggleReject($value)
    {
        DB::beginTransaction();
        try {
            $data = (object)$value['data'];
            $fee = TransactionFee::find($data->id);
            $fee->is_approved = 2;
            $fee->save();
            $this->triggerAudit(EkaTatuFee::class, Audit::ACTIVATED, 'ega_fee', $fee->id, ['status' => 0], ['status' => 2]);
            DB::commit();
            $this->alert('success', 'Ega fee successfully rejected');
            return redirect()->route('settings.fee-configurations.ega-fee');

        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            $this->alert('warning', 'Something went wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
