<?php

namespace App\Http\Livewire\Finance\CashBook;

use App\Enum\GeneralConstant;
use App\Enum\PaymentStatus;
use App\Models\ZmBill;
use App\Models\ZmPayment;
use App\Traits\CustomAlert;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class CashBookTable extends DataTableComponent
{
    use CustomAlert;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];

    public $data = [], $accountNumber, $query;

    public function mount($accountNumber) {
        $this->accountNumber = $accountNumber;
    }

    public function filterData($data)
    {
        $this->data = $data;
        $this->emit('$refresh');
    }

    public function builder(): Builder
    {
        $data   = $this->data;
        $filter = (new ZmPayment())->newQuery();

        if (isset($data['tax_type_id']) && $data['tax_type_id'] != GeneralConstant::ALL) {
            $filter->Where('tax_type_id', $data['tax_type_id']);
        }

        if (isset($data['range_start']) && isset($data['range_end'])) {
            $filter->WhereBetween('trx_time', [$data['range_start'],$data['range_end']]);
        }

        return $filter->where('ctr_acc_num', $this->accountNumber)
            ->whereHas('bill', function ($query) {
                $query->where('status', 'paid');
            })
            ->orderBy('trx_time', 'DESC');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['zm_bill_id', 'billable_type', 'billable_id']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class'   => 'table-bordered table-sm',
        ]);
        $this->setPerPageAccepted([10, 25, 50, 100]);
    }

    public function columns(): array
    {
        return [
            Column::make('Control No.', 'bill.control_number')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return $value ? $value : 'N/A';
                }),
            Column::make('Paid Amount', 'paid_amount')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return number_format($value, 2) . " $row->currency";
                })
                ->footer(function($rows) {
//                    dd(count($rows));
                    $value = $rows->sum('paid_amount') ?? 0;
                    return 'Total: ' . number_format($value, 2);
                }),
            Column::make('Tax Type', 'tax_type_id')
                ->label(fn ($row) => $row->bill->taxType->name ?? 'N/A')
                ->sortable()
                ->searchable(),
            Column::make('Business Name', 'billable')
                ->label(fn ($row) => $row->billable->business->name ?? 'N/A')
                ->sortable()
                ->searchable(),
            Column::make('Payer Name', 'payer_name')
                ->sortable()
                ->searchable(),
            Column::make('Description', 'bill.description')
                ->sortable()
                ->searchable(),
            Column::make('Paid On', 'trx_time')
                ->sortable()
                ->searchable(),
            LinkColumn::make('Action', 'zm_bill_id')
                ->title(fn($row) => 'View')
                ->location(fn($row) => route('payments.show', encrypt($row->zm_bill_id)))
                ->attributes(fn($row) => [
                    'class' => 'btn btn-sm btn-primary',
                ]),

        ];
    }
}
