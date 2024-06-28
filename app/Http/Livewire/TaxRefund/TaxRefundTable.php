<?php

namespace App\Http\Livewire\TaxRefund;

use App\Models\TaxAgentStatus;
use App\Models\TaxRefund\TaxRefund;
use App\Traits\WithSearch;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\TaxAgent;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;

class TaxRefundTable extends DataTableComponent
{

    public function builder(): Builder
    {
        return TaxRefund::query()->orderByDesc('id');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make("Name", "importer_name")
                ->searchable(),
            Column::make("Phone", "phone_number")
                ->searchable(),
            Column::make("Tax Amount Excl.", "total_exclusive_tax_amount")
                ->format(function ($value, $row) {
                    return $value ? number_format($value, 2) : 'N/A';
                }),
            Column::make("Payable Amount", "total_payable_amount")
                ->format(function ($value, $row) {
                    return $value ? number_format($value, 2) : 'N/A';
                }),
            Column::make("Payment Status", 'payment_status')
                ->view('returns.includes.payment-status'),
            Column::make('Action', 'id')
                ->view('tax-refund.includes.actions')
        ];
    }
}
