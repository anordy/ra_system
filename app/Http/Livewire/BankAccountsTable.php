<?php

namespace App\Http\Livewire;

use App\Models\Bank;
use App\Models\BankAccount;
use App\Traits\WithSearch;
use Exception;
use Illuminate\Support\Facades\Gate;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class BankAccountsTable extends DataTableComponent
{
    use CustomAlert;

    protected $model = BankAccount::class;

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
            Column::make('Bank Name', 'bank.name')
                ->sortable()
                ->searchable(),
            Column::make('Account Name', 'account_name')
                ->sortable()
                ->searchable(),
            Column::make('Account Number', 'account_number')
                ->sortable()
                ->searchable(),
            Column::make('Currency', 'currency')
                ->sortable()
                ->searchable(),
            Column::make('Action', 'id')
                ->view('payments.includes.bank-account-actions')
        ];
    }


    public function delete($id)
    {
        if (!Gate::allows('setting-bank-delete')) {
            abort(403);
        }

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
            $bank = BankAccount::find($data->id);
            if(is_null($bank)){
                abort(404);
            }
            $bank->delete();
            $this->flash('success', 'Bank Account Deleted Successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            report($e);
            $this->customAlert('warning', 'Something went wrong', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
