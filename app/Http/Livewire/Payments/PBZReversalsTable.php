<?php

namespace App\Http\Livewire\Payments;

use App\Enum\GeneralConstant;
use App\Enum\PaymentStatus;
use App\Models\PBZReversal;
use App\Models\PBZTransaction;
use App\Models\ZmBill;
use App\Traits\WithSearch;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PBZReversalsTable extends DataTableComponent
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
        $query = (new PBZReversal())->newQuery();

        if (isset($data['currency']) && $data['currency'] != GeneralConstant::ALL) {
            $query->where('currency', $data['currency']);
        }
        if (isset($data['range_start']) && isset($data['range_end'])) {
            $query->whereBetween('transaction_time', [$data['range_start'], $data['range_end']]);
        }

        if (isset($data['has_bill']) && $data['has_bill'] == GeneralConstant::YES){
            $query->whereHas('bill');
        }

        if (isset($data['has_bill']) && $data['has_bill'] == GeneralConstant::NO){
            $query->whereDoesntHave('bill');
        }

        return $query->with('bill');
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
            Column::make('Reversed Amount', 'amount')
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
                ->view('payments.pbz.includes.reversal-actions')
        ];
    }
}
