<?php

namespace App\Http\Livewire\Debt;

use App\Models\TaxType;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\BFO\BfoReturn;
use App\Models\Returns\Vat\VatReturn;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\MmTransferReturn;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Returns\EmTransactionReturn;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Models\Returns\LumpSum\LumpSumReturn;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Returns\StampDuty\StampDutyReturn;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class ReturnsTable extends DataTableComponent
{

    use LivewireAlert;
    public $taxType;
    public $tax;

    public function mount($taxType)
    {
        $this->taxType = $taxType;
        $this->tax = TaxType::where('code', $this->taxType)->first();
    }

    public function builder(): Builder
    {
        if ($this->taxType == TaxType::HOTEL || $this->taxType == TaxType::RESTAURANT || $this->taxType == TaxType::TOUR_OPERATOR) {
            return HotelReturn::query()->where('tax_type_id', $this->tax->id)->where('hotel_returns.status', '!=', ReturnStatus::COMPLETE);
        } else if ($this->taxType == TaxType::PETROLEUM) {
            return PetroleumReturn::query()->where('petroleum_returns.status', '!=', ReturnStatus::COMPLETE);
        } else if ($this->taxType == TaxType::EXCISE_DUTY_BFO) {
            return BfoReturn::query()->where('bfo_returns.status', '!=', ReturnStatus::COMPLETE);
        } else if ($this->taxType == TaxType::EXCISE_DUTY_MNO) {
            return MnoReturn::query()->where('mno_returns.status', '!=', ReturnStatus::COMPLETE);
        } else if ($this->taxType == TaxType::STAMP_DUTY) {
            return StampDutyReturn::query()->where('stamp_duty_returns.status', '!=', ReturnStatus::COMPLETE);
        } else if ($this->taxType == TaxType::VAT) {
            return VatReturn::query()->where('vat_returns.status', '!=', ReturnStatus::COMPLETE);
        } else if ($this->taxType == TaxType::LUMPSUM_PAYMENT) {
            return LumpSumReturn::query()->where('lump_sum_returns.status', '!=', ReturnStatus::COMPLETE);
        } else if ($this->taxType == TaxType::ELECTRONIC_MONEY_TRANSACTION) {
            return EmTransactionReturn::query()->where('em_transaction_returns.status', '!=', ReturnStatus::COMPLETE);
        } else if ($this->taxType == TaxType::MOBILE_MONEY_TRANSFER) {
            return MmTransferReturn::query()->where('mm_transfer_returns.status', '!=', ReturnStatus::COMPLETE);
        }
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['business_id', 'financial_month_id', 'tax_type_id']);
    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Tax Payer', 'business.taxpayer.first_name')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->business->taxpayer->first_name} {$row->business->taxpayer->last_name}";
                }),
            Column::make('Financial Month', 'financialMonth.name')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->financialMonth->name} {$row->financialMonth->year->code}";
                }),
            Column::make('Tax Type', 'taxtype.name'),
            Column::make('Total Debt', 'total_amount_due_with_penalties')
                ->format(function ($value, $row) {
                    return number_format($row->total_amount_due_with_penalties, 2);
                }),
            // Column::make('Action', 'id')->view('debts.returns.includes.actions'),

        ];
    }
}
