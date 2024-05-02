<?php

namespace App\Http\Livewire\Payments;

use App\Enum\GeneralConstant;
use App\Models\PBZStatement;
use App\Models\PBZTransaction;
use App\Traits\CustomAlert;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PBZPaymentsTable extends DataTableComponent
{
    use CustomAlert;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];

    public $data = [], $status, $statement;

    public function filterData($data)
    {
        $this->data = $data;
        $this->emit('$refresh');
    }

    public function mount($status = null, $statement = null){
        $this->status = $status;
        $this->statement = $statement;
    }

    public function builder(): Builder
    {
        if ($this->statement){
            return PBZTransaction::query()
                ->join('pbz_transaction_statement', 'pbz_transactions.id', '=', 'pbz_transaction_statement.pbz_transaction_id')
                ->where('pbz_transaction_statement.pbz_statement_id', $this->statement);
        } else {
            $data   = $this->data;
            $query = (new PBZTransaction())->newQuery();

            if (isset($data['currency']) && $data['currency'] != GeneralConstant::ALL) {
                $query->where('currency', $data['currency']);
            }

            if (isset($data['range_start']) && isset($data['range_end'])) {
                $query->whereBetween('transaction_time', [$data['range_start'], $data['range_end']]);
            } else {
                $query->whereBetween('transaction_time', [Carbon::now()->startOfMonth()->toDateString(), Carbon::now()->toDateString()]);
            }

            if (isset($data['has_bill']) && $data['has_bill'] == GeneralConstant::YES){
                $query->whereHas('bill');
            }

            if (isset($data['has_bill']) && $data['has_bill'] == GeneralConstant::NO){
                $query->whereDoesntHave('bill');
            }

            return $query->with('bill')->orderBy('created_at', 'desc');
        }
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setPerPageAccepted([15, 25, 50, 100]);

        if ($this->statement){
            $this->setPerPage(100);
        }
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
            Column::make('Transaction Time', 'transaction_time')->format(function ($value, $row){
                return $value->toDateTimeString();
            }),
            Column::make('Has Bill', 'created_at')
                ->view('payments.pbz.includes.has-bill'),
            Column::make('Actions', 'id')
                ->view('payments.pbz.includes.payment-actions')
        ];
    }
}
