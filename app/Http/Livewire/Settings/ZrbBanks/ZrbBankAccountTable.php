<?php

namespace App\Http\Livewire\Settings\ZrbBanks;

use App\Models\DualControl;
use App\Models\ZrbBankAccount;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ZrbBankAccountTable extends DataTableComponent
{
    use LivewireAlert, DualControlActivityTrait;

    public function builder(): Builder
    {
        return ZrbBankAccount::query()
            ->with('bank')
            ->orderBy('created_at', 'Desc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        $this->setTdAttributes(function (Column $column) {
            if ($column->isField('id')) {
                return [
                    'style' => 'width: 20%;',
                ];
            }

            return [];
        });
    }

    protected $listeners = ['confirmed'];

    public function columns(): array
    {
        return [
            Column::make('Account Name', 'account_name')
                ->sortable()
                ->searchable(),
            Column::make('Account Number', 'account_number')
                ->sortable()
                ->searchable(),
            Column::make('Bank name', 'bank_id')
                ->format(function ($value, $row) {
                    return $row->bank->name ?? 'N/A';  
                })
                ->sortable()
                ->searchable(),
            Column::make('Branch Name', 'branch_name')
                ->sortable()
                ->searchable(),
            Column::make('Currency', 'currency_iso')
                ->sortable()
                ->searchable(),
            Column::make('Approval Status', 'is_approved')
                ->format(function ($value, $row) {
                    if ($value == 0) {
                        return <<<HTML
                            <span style="border-radius: 0 !important;" class="badge badge-warning p-2" >Not Approved</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<<HTML
                            <span style="border-radius: 0 !important;" class="badge badge-success p-2" >Approved</span>
                        HTML;
                    } elseif ($value == 2) {
                        return <<<HTML
                            <span style="border-radius: 0 !important;" class="badge badge-danger p-2" >Rejected</span>
                        HTML;
                    } else {
                        return <<<HTML
                            <span style="border-radius: 0 !important;" class="badge badge-danger p-2" >Rejected</span>
                        HTML;
                    }
                })
                ->html(),
            Column::make('Action', 'id')->view('settings.zrb-bank-accounts.includes.actions'),
        ];
    }

    public function delete($id)
    {
        if (!Gate::allows('zrb-bank-account-delete')) {
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
            $zrbBankAccount = ZrbBankAccount::findOrFail(decrypt($data->id));
            $this->triggerDualControl(get_class($zrbBankAccount), $zrbBankAccount->id, DualControl::DELETE, 'deleting zrb bank account');
            $this->alert('success', DualControl::SUCCESS_MESSAGE,  ['timer'=>8000]);
            $this->flash('success', DualControl::SUCCESS_MESSAGE, [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            report($e);
            $this->alert('warning', 'Something whent wrong!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
