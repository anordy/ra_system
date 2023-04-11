<?php

namespace App\Http\Livewire\Payments;

use App\Models\MissingBankRecon;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class MissingBankReconTable extends DataTableComponent
{
    use CustomAlert;

    public $parameters = [];

    public function mount($parameters){
        $this->parameters = $parameters;
    }

    public function builder(): Builder
    {
        $query = MissingBankRecon::query()
            ->whereBetween('created_at', [$this->parameters['range_start'], $this->parameters['range_end']])
            ->orderBy('created_at', 'desc');

        if ($this->parameters['currency'] != 'all'){
            $query->where('currency', $this->parameters['currency']);
        }

        return $query;
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
            Column::make('Control No.', 'control_no')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return $value ? $value : 'Pending';
                }),
            Column::make('Payment Ref.', 'payment_ref')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return $value ?: 'N/A';
                }),
            Column::make('Paid Amount', 'credit_amount')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                }),
            Column::make('Currency', 'currency')
                ->sortable()
                ->searchable(),
            Column::make('Transaction Type', 'transaction_type')
                ->searchable()
                ->sortable(),
            Column::make('Transaction Date', 'transaction_date')
                ->format(function ($value, $row) {
                    return $value ? $value->toFormattedDateString() : 'N/A';
                }),
            Column::make('Transaction Origin', 'transaction_origin')
                ->format(function ($value, $row) {
                    return $value ? $value : 'N/A';
                }),
            Column::make('Payer Name', 'payer_name')
                ->format(function ($value, $row) {
                    return $value ?: 'N/A';
                }),
            Column::make('DR/CR', 'dr_cr')
                ->format(function ($value, $row) {
                    return $value ?: 'N/A';
                }),
        ];
    }
}
