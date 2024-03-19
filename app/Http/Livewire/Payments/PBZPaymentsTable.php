<?php

namespace App\Http\Livewire\Payments;

use App\Models\PBZTransaction;
use App\Traits\CustomAlert;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PBZPaymentsTable extends DataTableComponent
{
    use CustomAlert;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];

    public $data = [];

    public function filterData($data)
    {
        $this->data = $data;
        $this->emit('$refresh');
    }

    public function builder(): Builder
    {
        $data   = $this->data;
        $query = (new PBZTransaction())->newQuery();

        if (isset($data['currency']) && $data['currency'] != 'All') {
            $query->Where('currency', $data['currency']);
        }
        if (isset($data['range_start']) && isset($data['range_end'])) {
            $query->WhereBetween('transaction_time', [$data['range_start'],$data['range_end']]);
        }

        return $query->with('bill')->orderBy('created_at', 'desc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        $this->setPerPageAccepted([15, 25, 50, 100]);
    }

    public function columns(): array
    {
        return [
            Column::make('Control No.', 'control_number')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return $value ? $value : 'N/A';
                }),
            Column::make('Paid Amount', 'amount')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                }),
            Column::make('Currency', 'currency')
                ->sortable()
                ->searchable(),

            Column::make('Bank Ref', 'bank_ref'),
            Column::make('Transaction Time', 'transaction_time'),
            Column::make('Has Bill', 'created_at')
                ->view('payments.pbz.includes.has-bill'),
            Column::make('Actions', 'id')
                ->view('payments.pbz.includes.payment-actions')
        ];
    }
}
